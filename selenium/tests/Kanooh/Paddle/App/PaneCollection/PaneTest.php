<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PaneCollection\PaneTest.
 */

namespace Kanooh\Paddle\App\PaneCollection;

use Kanooh\Paddle\Apps\PaneCollection;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage\EditPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionDeleteModal;
use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionModal;
use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTableRow;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection\PaddlePaneCollectionPanelsContentType;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class to test the Pane Collection Panes.
 *
 * @package Kanooh\Paddle\App\PaneCollection
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
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
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->viewPage = new ViewPage($this);

        // Go to the login page and log in as Site manager.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('SiteManager');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new PaneCollection());
    }

    /**
     * Tests the basic configuration and functionality of the Pane Collection pane.
     *
     * @group panes
     */
    public function testPaneConfiguration()
    {
        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();

        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create a Pane Collection Pane and assert that there is no collection
        // selected by default.
        $content_type = new PaddlePaneCollectionPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // Assert that there is no pane collection selected.
        $label = $content_type->getForm()->paneCollectionSelection->getSelectedLabel();
        $this->assertEquals('< Pick a collection >', $label);

        // Assert that there are no top and bottom sections present.
        try {
            $this->byCss('.pane-section-top');
            $this->fail('There should be no top section available.');
        } catch (\Exception $e) {
            // Do nothing - all is fine.
        }

        try {
            $this->byCss('.pane-section-bottom');
            $this->fail('There should be no bottom section available.');
        } catch (\Exception $e) {
            // Do nothing - all is fine.
        }

        $modal->submit();
        $modal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Tests adding a Pane Collection to a page.
     *
     * @group panes
     */
    public function testPaneCollectionPane()
    {
        // Create a pane collection.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Edit the pane collection and add two panes.
        $pane_collection = paddle_pane_collection_load_by_title($title);

        /** @var PaneCollectionTableRow $row */
        $row = $this->configurePage->paneCollectionTable->getRowByTitle($pane_collection->title);
        $row->actions->linkLayout->click();
        $this->editPage->checkArrival();
        $this->editPage->display->waitUntilEditorIsLoaded();

        $content_type_1 = new CustomContentPanelsContentType($this);
        $body_1 = $this->alphanumericTestDataProvider->getValidValue();
        $content_type_1->body = $body_1;
        $this->editPage->display->getRandomRegion()->addPane($content_type_1);
        $this->waitUntilTextIsPresent($body_1);

        $content_type_2 = new CustomContentPanelsContentType($this);
        $body_2 = $this->alphanumericTestDataProvider->getValidValue();
        $content_type_2->body = $body_2;
        $this->editPage->display->getRandomRegion()->addPane($content_type_2);
        $this->waitUntilTextIsPresent($body_2);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();
        $this->waitUntilTextIsPresent('has been updated.');

        // Create a basic page to add the pane collection to.
        $nid = $this->contentCreationService->createBasicPage();

        // Add the Pane Collection.
        $this->addPaneCollectionToPage($nid, $title);

        // Go back to the Layout Page and assert that you find the pane with the pane
        // collection title in its content instead of the separate panes.
        $this->layoutPage->go($nid);
        $this->assertTextPresent('Pane Collection ' . $title);
        $this->assertTextNotPresent($body_1);
        $this->assertTextNotPresent($body_2);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Head to the Front End View page and assert that the content
        // of the panes within the pane collection are shown.
        $this->viewPage->go($nid);
        $this->assertTextPresent($body_1);
        $this->assertTextPresent($body_2);
    }

    /**
     * Asserts that the pane collection is not rendered anymore when deleted.
     */
    public function testDeletedPaneCollectionNotShown()
    {
        // Create a pane collection.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();

        $modal = new PaneCollectionModal($this);
        $modal->waitUntilOpened();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($title);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Create a basic page to add the pane collection to.
        $nid = $this->contentCreationService->createBasicPage();

        // Add the Pane Collection.
        $this->addPaneCollectionToPage($nid, $title);

        // Go back to the Layout Page and assert that you can find the title.
        $this->layoutPage->go($nid);
        $this->assertTextPresent('Pane Collection ' . $title);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Delete the Pane Collection.
        $this->configurePage->go();
        $row = $this->configurePage->paneCollectionTable->getRowByTitle($title);
        $row->actions->linkDelete->click();

        $delete_modal = new PaneCollectionDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->confirmButton->click();
        $delete_modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Go back to the Layout Page and assert that you cannot find the title.
        $this->layoutPage->go($nid);
        $this->assertTextNotPresent('Pane Collection ' . $title);
    }

    /**
     * Adds a pane collection to a page.
     *
     * @param $nid
     *   Node ID of the page.
     * @param $title
     *   Title of the pane collection.
     */
    protected function addPaneCollectionToPage($nid, $title)
    {
        // Add the Pane Collection.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new PaddlePaneCollectionPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $content_type->getForm()->paneCollectionSelection->selectOptionByLabel($title);

        $modal->submit();
        $modal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }
}
