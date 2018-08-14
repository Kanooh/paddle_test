<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\IncomingRSS\PaneTest.
 */

namespace Kanooh\Paddle\App\IncomingRSS;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\IncomingRSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedModal;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\IncomingRssPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Incoming RSS pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

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
     * Front end node view page.
     *
     * @var ViewPage
     */
    protected $frontendPage;

    /**
     * Basic page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

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

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new LayoutPage($this);
        $this->frontendPage = new ViewPage($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new IncomingRSS);
    }

    /**
     * Tests the basic configuration and functionality of the Incoming RSS pane.
     *
     * @group panes
     * @group incomingRss
     */
    public function testPaneConfiguration()
    {
        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Add a few Incoming RSS entities.
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        $feeds = array();
        for ($i = 0; $i < 2; $i++) {
            $this->configurePage->contextualToolbar->buttonAdd->click();
            $modal = new RSSFeedModal($this);
            $modal->waitUntilOpened();

            $feed_title = $this->alphanumericTestDataProvider->getValidValue();
            $modal->form->title->fill($feed_title);
            $modal->form->url->fill('http://feeds.bbci.co.uk/news/rss.xml');
            $modal->form->saveButton->click();
            $modal->waitUntilClosed();
            $this->waitUntilTextIsPresent('RSS feed saved.');
            $this->waitUntilTextIsPresent($feed_title);
            $row = $this->configurePage->feedTable->getRowByTitle($feed_title);
            $feeds[] = array('id' => $row->feedId, 'title' => $feed_title);
        }

        // Add the first incoming RSS feed to a pane in the node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        $content_type = new IncomingRssPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // Select the first Incoming RSS feed.
        $content_type->getForm()->incomingRssFeeds->selectOptionByLabel($feeds[0]['title']);

        // Test that the "Number of items" field accepts only positive numbers.
        $content_type->getForm()->numberOfItems->fill('ll');
        $modal->submit();
        $this->assertTextPresent('The number of items must be a valid number bigger than zero.');
        $content_type->getForm()->numberOfItems->fill(-1);
        $modal->submit();
        $this->assertTextPresent('The number of items must be a valid number bigger than zero.');
        $content_type->getForm()->numberOfItems->fill(0);
        $modal->submit();
        $this->assertTextPresent('The number of items must be a valid number bigger than zero.');
        $content_type->getForm()->numberOfItems->fill(4);

        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Now check the editing of the pane.
        $this->layoutPage->go($nid);
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        // Check that the values were correctly saved and edit the pane to
        // display the second feed.
        $this->assertEquals($content_type->getForm()->incomingRssFeeds->getSelectedValue(), $feeds[0]['id']);
        $content_type->getForm()->incomingRssFeeds->selectOptionByLabel($feeds[1]['title']);

        $this->assertTrue($content_type->getForm()->titleViewMode->isSelected());
        $content_type->getForm()->magazineViewMode->select();

        $this->assertEquals($content_type->getForm()->numberOfItems->getContent(), 4);

        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Tests the content shown through the pane.
     */
    public function testPaneContent()
    {
        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Add a few Incoming RSS entities.
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        // Prepare a list of sources to import.
        $sources = array(
            'animals' => array(
                'feed title' => $this->alphanumericTestDataProvider->getValidValue(),
                'source' => $this->base_url . '/selenium/tests/Kanooh/Paddle/assets/animals.xml',
                'feed item titles' => array(
                    'Cat',
                    'Dog',
                    'Blue whale',
                    'Sperm whale',
                    'Armadillo',
                    'Sponge',
                ),
                'pane configuration' => array(
                    'number_of_items' => 7,
                    'mode' => 'title',
                ),
            ),
            'music' => array(
                'feed title' => $this->alphanumericTestDataProvider->getValidValue(),
                'source' => $this->base_url . '/selenium/tests/Kanooh/Paddle/assets/music.xml',
                'feed item titles' => array(
                    'Madredeus',
                    'Ricchi e Poveri',
                    't.A.T.u.',
                    'Queen',
                    'Take That',
                    'One Direction',
                ),
                'pane configuration' => array(
                    'number_of_items' => 3,
                    'mode' => 'magazine',
                ),
            ),
        );

        // Create and import incoming rss entities.
        foreach ($sources as $info) {
            $this->configurePage->contextualToolbar->buttonAdd->click();
            $modal = new RSSFeedModal($this);
            $modal->waitUntilOpened();

            $modal->form->title->fill($info['feed title']);
            $modal->form->url->fill($info['source']);
            $modal->form->saveButton->click();
            $modal->waitUntilClosed();
            $this->waitUntilTextIsPresent('RSS feed saved.');
            $this->waitUntilTextIsPresent($info['feed title']);
        }

        // Add two panes in the node created.
        foreach ($sources as $name => $info) {
            $configuration = $info['pane configuration'] + array('title' => $info['feed title']);
            $sources[$name]['pane uuid'] = $this->addIncomingRSSPaneToNode($nid, $configuration);
        }

        // Go to the frontend and make sure that the correct feed items
        // are displayed and the pane configuration is applied.
        $this->adminViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        foreach ($sources as $info) {
            $pane = new IncomingRssPane(
                $this,
                $info['pane uuid'],
                '//div[@data-pane-uuid="' . $info['pane uuid'] . '"]'
            );

            $feed_items = $pane->getFeedItems();

            // Assert that the number of feed items displayed matches the
            // configuration.
            $expected_count = min(
                $info['pane configuration']['number_of_items'],
                count($info['feed item titles'])
            );
            $this->assertCount($expected_count, $feed_items);

            $row_counter = 1;
            foreach ($feed_items as $item) {
                $this->assertContains($item->title, $info['feed item titles']);

                $link = 'https://en.wikipedia.org/wiki/' . str_replace(' ', '_', $item->title);
                $this->assertEquals($link, $item->link);

                // Based on the view mode, assert that some fields are
                // present or not.
                $this->assertNotEquals($info['pane configuration']['mode'] == 'title', (bool) $item->description);
                $this->assertNotEquals($info['pane configuration']['mode'] == 'title', (bool) $item->thumbnail);
                $this->assertNotEquals($info['pane configuration']['mode'] == 'title', (bool) $item->created);

                if ($info['pane configuration']['mode'] == 'magazine') {
                    // Verify the date format.
                    $this->assertRegExp('/^[0-3]\d\/(0|1)\d\/\d{4}$/', $item->created);
                }

                // Assert that the row of the RSS Feed has the correct class assigned based on the source.
                try {
                    $this->byCssSelector('.views-row-' . $row_counter . '.en-wikipedia-org');
                } catch (\PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
                    $this->fail('The class based on the feed item source should be shown.');
                }
                $row_counter++;
            }
        }
    }

    /**
     * Adds an Incoming RSS pane to a node.
     *
     * @param string $nid
     *   The node id of the node to which to add the pane.
     * @param array $values
     *   The configuration values of the pane.
     *
     * @return string
     *   The pane uuid.
     */
    protected function addIncomingRSSPaneToNode($nid, $values)
    {
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new IncomingRssPanelsContentType($this);
        $callable = new SerializableClosure(
            function ($modal) use ($content_type, $values) {
                $content_type->getForm()->incomingRssFeeds->selectOptionByLabel($values['title']);
                $content_type->getForm()->numberOfItems->fill($values['number_of_items']);
                $view_mode = $values['mode'] . 'ViewMode';
                $content_type->getForm()->{$view_mode}->select();
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        return $pane_uuid;
    }
}
