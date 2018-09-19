<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase.
 */

namespace Kanooh\Paddle\Core\Node;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NewNodeHasResponsibleAuthorTest extends WebDriverTestCase
{

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The user session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Tests that new nodes have responsible author equal to the creation user.
     *
     * @group responsibleAuthorTestBase
     */
    public function testFieldWithInvalidInput()
    {
        // Log in as editor.
        $this->userSessionService->login('Editor');

        // Create the node.
        $nid = $this->contentCreationService->createBasicPageViaUI();

        // Make sure the responsible author is the same as the creation user.
        $this->nodeEditPage->go($nid);
        $expected_author = 'demo_editor (' . $this->userSessionService->getCurrentUserId() . ')';
        $this->assertEquals($expected_author, $this->nodeEditPage->responsibleAuthor->getContent());

        // Make sure we can still change it.
        $new_author = 'demo_chief_editor';
        $this->nodeEditPage->responsibleAuthor->fill($new_author);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        // Cancel the page to prevent subsequent tests to be bothered by an
        // alert window.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->nodeSummary->showAllMetadata();

        // Check that the responsible author is shown in the node metadata
        // summary.
        $responsible_author = $this->adminNodeViewPage->nodeSummary->getMetadata('created', 'page-responsible-author');

        $this->assertEquals($new_author, $responsible_author['value']);
    }
}
