<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OutgoingRSS\PaneTest.
 */

namespace Kanooh\Paddle\App\OutgoingRSS;

use Drupal\Component\Utility\Random;
use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\OutgoingRSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsModal;
use Kanooh\Paddle\Pages\Element\Pane\OutgoingRssListPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OutgoingRssPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Outgoing RSS feed pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
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
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Landing page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Front end node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new LayoutPage($this);
        $this->viewPage = new ViewPage($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OutgoingRSS);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the basic configuration and functionality of the Outgoing RSS pane.
     *
     * @group panes
     * @group outgoingRss
     */
    public function testPane()
    {
        // Create a node to use for the pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Create a few Outgoing RSS feed.
        $entities = array();
        for ($i = 0; $i < 3; $i++) {
            // Create a RSS feed.
            $fid = $this->addOutgoingRssEntity();
            $entities[] = entity_load_single('paddle_outgoing_rss_feed', $fid);
        }

        // First add 1 feed to the pane.
        $pane_uuid = $this->addRssFeedListToPane($nid, array($entities[0]->fid));

        // Go to the front-end and make sure the Outgoing RSS feeds list pane
        // is displayed fine.
        $this->viewPage->go($nid);
        $this->assertOutgoingRssFeedsListPane(array($entities[0]), $pane_uuid);

        // Now edit the pane to deselect the first feed and select the other 2.
        $this->layoutPage->go($nid);
        $pane = new OutgoingRssListPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type = new OutgoingRssPanelsContentType($this);
        $form = $content_type->getForm();
        $form->rssFeeds[$entities[0]->fid]->uncheck();
        $form->rssFeeds[$entities[1]->fid]->check();
        $form->rssFeeds[$entities[2]->fid]->check();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go to the front-end and make sure the Outgoing RSS feeds list pane
        // is displayed fine.
        $this->viewPage->go($nid);
        $this->assertOutgoingRssFeedsListPane(array($entities[1], $entities[2]), $pane_uuid);
    }

    /**
     * Tests that the icons are visible for rss entries in the pane.
     *
     * @group regression
     */
    public function testRssIconVisibility()
    {
        // Create a node to use for the pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Create an outgoing RSS entity.
        $fid = $this->addOutgoingRssEntity();

        // Add the rss to a pane in the basic page.
        $pane_uuid = $this->addRssFeedListToPane($nid, array($fid));

        // Go to the node view and verify that the icon is visible inside the link.
        $this->viewPage->go($nid);
        $pane = new OutgoingRssListPane($this, $pane_uuid, '');
        $link = end($pane->feedsList);
        $this->assertTrue($link->byXPath('./i')->displayed());
    }

    /**
     * Helper function to create an Outgoing Rss entity.
     *
     * @return int
     *   The id of the created entity.
     */
    protected function addOutgoingRssEntity()
    {
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        $title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($title);
        $modal->form->basicPageCheckBox->check();
        $modal->form->saveButton->click();
        $this->configurePage->feedTable->waitUntilTableUpdated($title);
        $row = $this->configurePage->feedTable->getRowByTitle($title);

        return $row->fid;
    }

    /**
     * Add a Outgoing RSS feed list pane to a node.
     *
     * @param string $nid
     *   The node id of the node to which to add the RSS feeds list pane.
     * @param array $feeds
     *   Array with the entity IDs of the feed to add to the pane.
     *
     * @return string
     *   The pane uuid.
     */
    protected function addRssFeedListToPane($nid, $feeds)
    {
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new OutgoingRssPanelsContentType($this);
        $callable = new SerializableClosure(
            function ($modal) use ($content_type, $feeds) {
                foreach ($feeds as $id) {
                    $content_type->getForm()->rssFeeds[$id]->check();
                }
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        return $pane_uuid;
    }

    /**
     * Check the rendering of the Outgoing RSS feed pane on the front-end.
     *
     * @param array $feeds
     *   Array containing the feed entities displayed in the list.
     * @param string $pane_uuid
     *   The uuid of the pane to which the form has been added.
     */
    protected function assertOutgoingRssFeedsListPane($feeds, $pane_uuid)
    {
        // Get the pane element from the page.
        $pane = new OutgoingRssListPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $links = $pane->feedsList;
        foreach ($feeds as $feed) {
            $link = $links[$feed->fid];
            $this->assertNotNull($link);
            $this->assertEquals(strtolower($link->text()), strtolower($feed->title));
            $this->assertTrue(strpos($link->attribute('href'), $feed->path) !== false);
        }
    }
}
