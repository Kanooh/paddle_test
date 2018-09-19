<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\PaneSectionsTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as NodeAdminViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for the pane sections of different panes.
 */
abstract class PaneSectionsTestBase extends WebDriverTestCase
{

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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
     * Get the 'Page layout' page belonging to a certain node type.
     *
     * @return \Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplayPage
     *   The 'Page layout' page.
     */
    protected function getLayoutPage()
    {
        return new LayoutPage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->addContentPage = new AddPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Make sure all content types are discoverable in the internal link
     * autocomplete in the pane sections.
     *
     * @group modals
     * @group panes
     */
    public function testInternalLinkContentTypes()
    {
        // Create a node to test with.
        $nid = $this->setupNode();

        // Navigate to 'Page layout'.
        $layout_page = $this->getLayoutPage();
        $layout_page->go($nid);

        // Select a region to put a pane on.
        $region = $layout_page->display->getRandomRegion();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($custom_content_pane);

        // Fill in a link.
        $custom_content_pane->topSection->enable->check();
        $custom_content_pane->topSection->text->fill('link text');
        $custom_content_pane->topSection->urlTypeRadios->internal->select();
        $custom_content_pane->topSection->internalUrl->fill('node/' . $nid);

        // Ensure we get only 1 result, and select it.
        $auto_complete = new AutoComplete($this);
        $auto_complete->waitUntilSuggestionCountEquals(1);
        $auto_complete->pickSuggestionByPosition(0);

        // Submit and wait until closed to ensure there are no validation errors.
        $modal->submit();
        $modal->waitUntilClosed();

        // Prevent alert box on following tests.
        $layout_page->contextualToolbar->buttonSave->click();
        $admin_view = new NodeAdminViewPage($this);
        $admin_view->checkArrival();
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
