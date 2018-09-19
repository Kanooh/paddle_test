<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\NewsOverviewTest.
 */

namespace Kanooh\Paddle\App\News;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\NewsItem\NewsItem;
use Kanooh\Paddle\Pages\Element\Pane\NewsOverviewPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonNotPresentException;
use Kanooh\Paddle\Pages\Node\EditPage\NewsPage as EditNewsPage;
use Kanooh\Paddle\Pages\Node\ViewPage\NewsOverviewPageViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\NewsViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle News overview page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NewsOverviewTest extends WebDriverTestCase
{

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The app managing service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * The news edit page.
     *
     * @var EditNewsPage
     */
    protected $editNewsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The content region configuration page.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * The utility class for common function for content regions.
     *
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * The front end view page for the news overview.
     *
     * @var NewsOverviewPageViewPage
     */
    protected $frontEndNewsOverviewPage;

    /**
     * The panels display of a contact person.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * Instance of the ContentCreationService used to create content.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * A news node displayed in the front end.
     *
     * @var NewsViewPage
     */
    protected $newsViewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionUtility = new ContentRegionUtility($this);
        $this->editNewsPage = new EditNewsPage($this);
        $this->frontEndNewsOverviewPage = new NewsOverviewPageViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->newsViewPage = new NewsViewPage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the News app.
        $this->appService->enableApp(new News);
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
     * Tests the regions on the news overview page.
     *
     * @group news
     */
    public function testNewsOverviewRegions()
    {
        // Set up test content.
        $test_content['all_pages']['right'] = $this->random->name(64);
        $test_content['all_pages']['bottom'] = $this->random->name(64);

        // Go to the content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Click 'Edit content for all pages' and add two panes: one to the
        // right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->links->linkEditContentForAllPages,
            $test_content['all_pages']['right'],
            $test_content['all_pages']['bottom']
        );

        // Go to the front end view page and verify that the region panes are
        // not shown.
        $this->frontEndNewsOverviewPage->go();
        $this->assertTextNotPresent($test_content['all_pages']['right']);
        $this->assertTextNotPresent($test_content['all_pages']['bottom']);

        // Go back to the administrative node view and verify that the content
        // region panes are not shown.
        $previewToolbar = new PreviewToolbar($this);
        $previewToolbar->closeButton->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextNotPresent($test_content['all_pages']['right']);
        $this->assertTextNotPresent($test_content['all_pages']['bottom']);

        // Click on 'Page layout' and add some custom panes.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Add two custom content panes, one to the right region, and one to the
        // bottom region.
        $custom_pane_content = array(
            'right' => $this->random->name(64),
            'bottom' => $this->random->name(64),
        );
        $regions = $this->layoutPage->display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $custom_pane_content['right'];
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $custom_pane_content['bottom'];
        $regions['bottom']->addPane($custom_content_pane);

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');

        // Verify that all panes except for the global ones are shown in the
        // regions.
        $this->assertTextNotPresent($test_content['all_pages']['right']);
        $this->assertTextNotPresent($test_content['all_pages']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go to the frontend view and check that the new panes are shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontEndNewsOverviewPage->waitUntilPageIsLoaded();
        $this->assertTextNotPresent($test_content['all_pages']['right']);
        $this->assertTextNotPresent($test_content['all_pages']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);
    }


    /**
     * Tests the top news and news list on the news overview page.
     *
     * @group editing
     * @group news
     */
    public function testNewsOverviewContentPanes()
    {
        $this->administrativeNodeViewPage->go($this->frontEndNewsOverviewPage->getNodeId());
        try {
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        } catch (ToolbarButtonNotPresentException $ex) {
            // Ignore this exception because the overview page is already
            // published if the publish button is not present any more.
        }

        // First delete all news items created by previous test methods.
        $cleanUpService = new CleanUpService($this);
        $cleanUpService->deleteEntities('node', 'news_item');

        // Create more than 10 news items to make sure each one replaces the
        // previous one in the top pane and then goes into the view generated
        // content.
        $news_data = array();
        for ($i = 0; $i < NewsOverviewPane::ITEMS_PER_PAGE + 2; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue(8);
            $body = $this->random->name(600);

            // Insert a few tags so we can test if they will be stripped.
            $body = substr_replace($body, '<strong>', 100, 0);
            $body = substr_replace($body, '</strong>', 200, 0);
            $body = substr_replace($body, '<img src="example.png" />', 500, 0);

            $nid = $this->contentCreationService->createNewsItem($title);
            $has_image = rand(0, 1);

            // Add the latest news to the beginning of the list to mirror the
            // page display as close as possible.
            array_unshift(
                $news_data,
                array(
                    'nid' => $nid,
                    'title' => $title,
                    'body' => $body,
                    'has_image' => $has_image,
                )
            );

            // Add an image and body to the news item.
            $this->editNewsPage->go($nid);
            if ($has_image) {
                $this->editNewsPage->newsForm->leadImage->chooseImage();
            }
            $this->editNewsPage->newsForm->body->setBodyText($body);

            // Save.
            $this->editNewsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Publish the news item.
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->frontEndNewsOverviewPage->go($this->frontEndNewsOverviewPage->getNodeId());

            // Check that the node we just created is shown in the "Top news"
            // pane. It is the newest one after all.
            $news_item = $this->frontEndNewsOverviewPage->topNewsPane->newsItem;
            $expected = reset($news_data);
            $this->assertNewsItem($news_item, $expected, true);

            // Check that the pager appears only when the items are more than 10.
            $this->assertEquals(($i > NewsOverviewPane::ITEMS_PER_PAGE), $this->frontEndNewsOverviewPage->newsOverviewPane->hasPager());

            // Check the number of items in the view pane.
            $news_items = $this->frontEndNewsOverviewPage->newsOverviewPane->getNewsItems();
            $num_items = $i > NewsOverviewPane::ITEMS_PER_PAGE ? NewsOverviewPane::ITEMS_PER_PAGE : $i;
            $this->assertEquals($num_items, count($news_items));

            // Now check the view pane news items.
            foreach ($news_data as $index => $data) {
                if ($index == 0) {
                    // This news item is in the top news pane.
                    continue;
                }
                if ($index == NewsOverviewPane::ITEMS_PER_PAGE + 1) {
                    // This item is already on the second page of the view.
                    // Check that we are on page 1 of 2 pages total.
                    $this->assertEquals(1, $this->frontEndNewsOverviewPage->newsOverviewPane->pager->getCurrentPage());
                    $this->assertEquals(2, $this->frontEndNewsOverviewPage->newsOverviewPane->pager->getPageTotal());

                    // Go to the next page.
                    $this->frontEndNewsOverviewPage->newsOverviewPane->pager->nextLink->click();
                    $this->frontEndNewsOverviewPage->checkArrival();

                    // Check that we are now on page 2 of 2 pages total.
                    $this->assertEquals(2, $this->frontEndNewsOverviewPage->newsOverviewPane->pager->getCurrentPage());
                    $this->assertEquals(2, $this->frontEndNewsOverviewPage->newsOverviewPane->pager->getPageTotal());

                    // On the second page we shouldn't have top news.
                    $this->assertFalse($this->frontEndNewsOverviewPage->hasTopNewsPane());

                    // Reset the pane index.
                    $index = 1;
                }

                // Assert that we found the date, image, title, body and url of
                // the news item in the view pane.
                // Only test the read more link for the first item, this causes
                // two additional page requests and is very slow.
                $test_read_more = !(bool) ($index - 1);
                $this->assertNewsItem($this->frontEndNewsOverviewPage->newsOverviewPane->getNewsItem($index - 1), $data, $test_read_more);
            }
        }
    }

    /**
     * Checks that the properties of the given news item match the expectations.
     *
     * @param NewsItem $news_item
     *   The news item to check.
     * @param array $expected
     *   An array with expected values, with the following keys:
     *   - 'nid': The node ID.
     *   - 'has_image': True if the news item has an image, false otherwise.
     *   - 'title': The title.
     *   - 'body': The body text.
     * @param bool $test_read_more
     *   Whether or not to test the read more link.
     */
    protected function assertNewsItem(NewsItem $news_item, array $expected, $test_read_more = false)
    {
        // Check if the correct node is shown.
        $this->assertEquals($expected['nid'], $news_item->getNodeId());

        // Check the date. It should have the month fully written out, and the
        // day should be without a leading zero.
        $this->assertEquals(date('j F Y'), $news_item->getDateString());

        // Check that the image is (not) present.
        $this->assertEquals($expected['has_image'], $news_item->hasImage());

        // Check if the correct image is shown.
        // @todo This currently is always 'sample_image.jpg'. Test with a random
        //   image from a curated set instead.
        // @see https://one-agency.atlassian.net/browse/KANWEBS-2305
        if ($expected['has_image']) {
            // If the same filename is used for multiple images, Drupal adds a
            // suffix with a number to it. Just check the base name.
            $this->assertTrue(strpos($news_item->getImageFileName(), 'sample_image') !== false);
        }

        // Check if the title is correct.
        $this->assertEquals($expected['title'], $news_item->getTitle());

        // Check out that body. It has been truncated and stripped of tags, so
        // check if it fits.
        $body_found = $news_item->getBody();
        $this->assertTrue(strpos(strip_tags($expected['body']), $body_found) === 0);

        // Make sure there are no tags in the body text.
        $this->assertEquals(strip_tags($body_found), $body_found);

        // Click the read more link and check if it leads to the right page.
        // Unfortunately we cannot check the validity of the URL directly since
        // these URLs are aliased.
        // Only test this when requested, this is a very slow process.
        if ($test_read_more) {
            $news_item->readMoreLink->click();
            $this->newsViewPage->checkArrival();
            $this->assertEquals($expected['nid'], $this->newsViewPage->getNodeId());

            // Then come back to the news overview page so the test can continue
            // where it left off.
            $this->frontEndNewsOverviewPage->go();
        }
    }
}
