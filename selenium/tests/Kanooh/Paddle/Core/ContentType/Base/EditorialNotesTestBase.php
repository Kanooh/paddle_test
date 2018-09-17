<?php

/**
 * @file
 * Contains \Kanooh\Paddle\EditorialNotesTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Element\EditorialNote\EditorialNote;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class EditorialNotesTestBase extends WebDriverTestCase
{
    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Tests editorial notes.
     **
     * @group contentType
     * @group editing
     * @group editorialNotesTestBase
     */
    public function testEditorialNotes()
    {
        // Login as the first user.
        $this->userSessionService->login('ChiefEditor');

        // Create a node and go to the edit page.
        $nid = $this->setupNode();
        $this->editPage->go($nid);

        // Create an editorial note.
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addEditorialNote($body);

        /** @var EditorialNote $note */
        $note = reset($this->editPage->editorialNotes);

        $expected = array(
            'body' => $body,
            'delete_link_displayed' => true,
            'username' => $this->userSessionService->getCurrentUserName(),
        );
        $this->assertNoteContainsCorrectInfo($note, $expected);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Login as another user with less permissions.
        $this->userSessionService->switchUser('Editor');
        $this->editPage->go($nid);

        // Check the editorial note created by the first user.
        $note = reset($this->editPage->editorialNotes);
        $expected ['delete_link_displayed'] = false;
        $this->assertNoteContainsCorrectInfo($note, $expected);

        // Now create an editorial note for the current user.
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addEditorialNote($body);

        // Check that all is well displayed for the new note.
        $expected = array(
            'body' => $body,
            'delete_link_displayed' => true,
            'username' => $this->userSessionService->getCurrentUserName(),
        );
        $this->assertNoteContainsCorrectInfo($note, $expected);
    }

    /**
     * Checks that an editorial note has the correct properties.
     *
     * @param \Kanooh\Paddle\Pages\Element\EditorialNote\EditorialNote $actual
     *   The EditorialNote object to check.
     * @param array $expected
     *   The values we expect to find.
     */
    public function assertNoteContainsCorrectInfo(EditorialNote $actual, array $expected)
    {
        $current_day = date('d/m/Y');
        $this->assertEquals($expected['body'], $actual->body);
        $this->assertEquals($expected['username'], $actual->username);
        if ($expected['delete_link_displayed']) {
            $this->assertNotNull($actual->linkDelete);
        } else {
            $this->assertNull($actual->linkDelete);
        }
        $this->assertContains($current_day, $actual->date);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
