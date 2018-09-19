<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Wysiwyg\InternalLinksTest.
 */

namespace Kanooh\Paddle\Core\Wysiwyg;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Tests if an internal link can be added in the Wysiwyg.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InternalLinksTest extends WebDriverTestCase
{
    /**
     * The 'add content' page.
     *
     * @var AddPage
     */
    protected $addPage;

    /**
     * The administrative node view.
     *
     * @var ViewPage
     */
    protected $administrativeNodeView;

    /**
     * Data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

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
     * The frontend node view page.
     *
     * @var NodeViewPage
     */
    protected $nodeViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addPage = new AddPage($this);
        $this->administrativeNodeView = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->nodeViewPage = new NodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests if an internal link can be added in the Wysiwyg.
     *
     * For the moment this only tests the basic use case of selecting an
     * existing page using the keyboard.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1766#
     *
     * @group wysiwyg
     */
    public function testInternalLinks()
    {
        // Create a basic page.
        $this->addPage->go();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->addPage->createNode('BasicPage', $title);

        // Click on "Page properties".
        $this->administrativeNodeView->checkArrival();
        $this->administrativeNodeView->contextualToolbar->buttonPageProperties->click();

        // Click the "Link" icon in the wysiwyg.
        $this->editPage->checkArrival();
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonLink->click();

        // Type the page title in the "Link" field.
        $modal = $this->editPage->body->modalLink;
        $modal->waitUntilOpened();
        $modal->linkInfoForm->link->fill($title);

        // Check that the page title appears in the autocomplete results.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);

        // Use the arrow keys to select the result, and press enter to confirm.
        $this->keys(Keys::DOWN . Keys::ENTER);

        // Verify that a link to the page has been placed in the wysiwyg.
        $parts = parse_url($this->base_url);
        $base_path = !empty($parts['path']) ? '/' . trim($parts['path'], '/') : '';
        $this->editPage->body->checkBodyByXPath('//a[@data-cke-saved-href = "' . $base_path . '/node/' . $nid . '"]');

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeView->checkArrival();

        // Click on "Preview revision".
        $this->administrativeNodeView->contextualToolbar->buttonPreviewRevision->click();
        $this->nodeViewPage->checkArrival();

        // Check that the link appears in the main content.
        $this->byXPath('//div[@id = "block-system-main"]//div[@id = "node-' . $nid . '"]//a[text() = "' . $title . '"]');
    }

    /**
     * Tests that the autocomplete suggestions are resized together with the modal.
     *
     * @group wysiwyg
     */
    public function testLongInternalLinks()
    {
        // Create a page title.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Edit the page.
        $this->editPage->go($nid);

        // Open the link modal.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonLink->click();
        $modal = $this->editPage->body->modalLink;
        $modal->waitUntilOpened();

        // Type the title and wait for the autocomplete to open.
        $modal->linkInfoForm->link->fill($title);
        $autocomplete = new AutoComplete($this);
        $suggestions = $autocomplete->getSuggestionsAsElements();

        // Get the first suggestion width.
        $current_autocomplete_width = (int)$suggestions[0]->css('width');

        // Make the link modal bigger.
        $current_modal_size = $modal->getContentSize();
        $modal->resize($current_modal_size['width'] + 100, 400);

        // Verify that the autocomplete suggestions got resized.
        $this->assertEquals($current_autocomplete_width + 100, (int)$suggestions[0]->css('width'));
    }
}
