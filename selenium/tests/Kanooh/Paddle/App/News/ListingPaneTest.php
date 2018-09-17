<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\News;

use Drupal\Component\Utility\Random;
use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\DeleteModal;
use Kanooh\Paddle\Pages\Node\EditPage\NewsPage as EditNewsPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the listing pane with the News view modes.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends WebDriverTestCase
{
    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * The assets library page.
     *
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Instance of the ContentCreationService used to create content.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The news edit page.
     *
     * @var EditNewsPage
     */
    protected $editNewsPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The layout page.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->assetsPage = new AssetsPage($this);
        $this->editNewsPage = new EditNewsPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->random = new Random();

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new News);
    }

    /**
     * Tests the News view modes in the listing pane.
     *
     * @group news
     * @group panes
     * @group listingPane
     */
    public function testListingPane()
    {
        // Create 4 news items with an image, and one without an image.
        $news_items = array();
        for ($i = 0; $i < 5; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue(12);
            $nid = $this->contentCreationService->createNewsItem($title);
            $news_items[$nid] = array(
                'title' => $title,
                'has_image' => $i < 4,
            );

            // Upload an image for all items except the last one.
            $this->editNewsPage->go($nid);
            if ($i < 4) {
                $this->editNewsPage->newsForm->leadImage->chooseImage();
            }

            // Save.
            $this->editNewsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Publish.
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Pop off the last item, this needs to be tested separately.
        end($news_items);
        $imageless_news_item_nid = key($news_items);
        $imageless_news_item = array_pop($news_items);

        // Create a landing page.
        $landing_page = $this->contentCreationService->createLandingPage();

        // Go to the LP's layout page.
        $this->panelsContentPage->go($landing_page);

        // Add a new listing pane.
        $region = $this->panelsContentPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $listing_pane = new ListingPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($listing_pane);

        // Make sure the News view modes are disabled by default.
        $this->assertFalse($listing_pane->viewModeNewsShortRadioButton->isEnabled());
        $this->assertFalse($listing_pane->viewModeNewsExtendedRadioButton->isEnabled());

        // Select news item content type, make sure the view modes become
        // enabled.
        $listing_pane->newsItemCheckBox->check();
        $this->assertTrue($listing_pane->viewModeNewsShortRadioButton->isEnabled());
        $this->assertTrue($listing_pane->viewModeNewsExtendedRadioButton->isEnabled());

        // Select basic page content type as well, make sure the News view modes
        // are disabled again.
        $listing_pane->basicPageCheckBox->check();
        $this->assertFalse($listing_pane->viewModeNewsShortRadioButton->isEnabled());
        $this->assertFalse($listing_pane->viewModeNewsExtendedRadioButton->isEnabled());

        // Deselect basic page content type again. Make sure the News view modes
        // are enabled again.
        $listing_pane->basicPageCheckBox->uncheck();
        $this->assertTrue($listing_pane->viewModeNewsShortRadioButton->isEnabled());
        $this->assertTrue($listing_pane->viewModeNewsExtendedRadioButton->isEnabled());

        // Select the News Short view mode.
        $listing_pane->viewModeNewsShortRadioButton->select();

        // Save the configuration.
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the page.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure the news items are shown correctly.
        $this->assertNewsItemsPresent($news_items, 'short');
        $this->assertNewsItemsPresent(array($imageless_news_item_nid => $imageless_news_item), 'short');

        // Go back to the layout page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->panelsContentPage->checkArrival();

        // Add a new listing pane.
        $region = $this->panelsContentPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $listing_pane = new ListingPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($listing_pane);

        // Filter by news item, and set the view mode to News Extended.
        $listing_pane->newsItemCheckBox->check();
        $listing_pane->viewModeNewsExtendedRadioButton->select();

        // Save the configuration.
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the page.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure the news items are shown correctly. The news item without
        // an image should fall back to the 'short' view mode.
        $this->assertNewsItemsPresent($news_items, 'extended');
        $this->assertNewsItemsPresent(array($imageless_news_item_nid => $imageless_news_item), 'short');

        // Now delete a asset to make sure the listing with extended news view
        // is not broken if there is no lead image.
        // See https://one-agency.atlassian.net/browse/KANWEBS-2766.
        $this->assetsPage->go();
        $atoms = $this->assetsPage->library->items;

        // Delete the last atom we added.
        $atoms[0]->deleteLink->click();
        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();

        // Make the change in the test array as well.
        $nids = array_keys($news_items);
        $news_items[$nids[3]]['has_image'] = false;

        // Go back to the admin node view.
        $this->administrativeNodeViewPage->go($landing_page);

        // Make sure the news items are still there.
        $this->assertNewsItemsPresent($news_items, 'extended');
    }

    /**
     * Test the "Detailed news view" for the listing pane.
     */
    public function testDetailedListingView()
    {
        // Create 4 image atoms.
        $atoms = array();
        for ($i = 0; $i < 4; $i++) {
            $atoms[] = $this->assetCreationService->createImage();
        }

        // Create 5 news items - one without an image, only one with a summary.
        $news_items = array();
        for ($i = 0; $i < 5; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue(12);
            $nid = $this->contentCreationService->createNewsItem($title);
            $news_items[$nid] = array(
              'title' => $title,
              'has_image' => $i < 4,
              'has_summary' => $i == 0,
            );

            $this->editNewsPage->go($nid);

            // Add body for the news item.
            $news_items[$nid]['body'] = $this->alphanumericTestDataProvider->getValidWordsValue(250);
            $this->editNewsPage->body->waitUntilReady();
            $this->editNewsPage->body->setBodyText($news_items[$nid]['body']);

            // Upload an image for all items except the last one.
            if ($i < 4) {
                $this->editNewsPage->newsForm->leadImage->selectAtom($atoms[$i]['id']);
            }

            // Add a summary only for the first news.
            if ($i == 0) {
                $news_items[$nid]['summary'] = $this->alphanumericTestDataProvider->getValidWordsValue(260);
                $this->editNewsPage->teaserToggleLink->click();
                $this->editNewsPage->waitUntilTeaserIsDisplayed();
                $this->editNewsPage->teaser->fill($news_items[$nid]['summary']);
            }

            // Save.
            $this->editNewsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Publish.
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a basic page and add the listing pane to it.
        $nid = $this->contentCreationService->createBasicPage();

        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $listing_pane = new ListingPanelsContentType($this);

        $callable = new SerializableClosure(
            function () use ($listing_pane) {
                $listing_pane->newsItemCheckBox->check();
                $listing_pane->viewModeNewsDetailedRadioButton->select();
            }
        );
        $region->addPane($listing_pane, $callable);

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure the node are properly displayed in this view mode.
        $this->assertNewsItemsPresent($news_items, 'detailed');
    }

    /**
     * Asserts that a given list of news items are shown correctly on the page.
     *
     * @param array $news_items
     *   List of news item titles, keyed by nid.
     * @param string $news_view_mode
     *   'short' or 'extended'
     */
    public function assertNewsItemsPresent($news_items, $news_view_mode)
    {
        foreach ($news_items as $nid => $news_item) {
            // Make sure the node is displayed in the correct view mode.
            $xpath = '//div[contains(@class, "list-news-item-' . $news_view_mode .
              '") and @data-news-item-nid="' . $nid . '"]';
            $news_element = $this->byXpath($xpath);

            // Make sure the node's title is visible.
            $title = $news_item['title'];
            $xpath = './/a//span[@class="title"]';
            $title_element = $news_element->elements($news_element->using('xpath')->value($xpath));
            $this->assertTextPresent($title, $title_element[0]);

            // Check for the featured image.
            if ($news_view_mode == 'extended' || $news_view_mode == 'detailed') {
                $xpath = './/a//span[@class="thumbnail"]//img';
                $image_elements = $news_element->elements($news_element->using('xpath')->value($xpath));
                if ($news_item['has_image']) {
                    $this->assertNotNull($image_elements[0]);
                } else {
                    $this->assertNull($image_elements[0]);
                }
                // Check that the news element has no 'has-image' class.
                $classes = explode(' ', $news_element->attribute('class'));
                $this->assertEquals($news_item['has_image'], in_array('has-image', $classes));
            } else {
                // If not extended, verify there are no images present.
                $xpath = './/a//span[@class="thumbnail"]//img';
                $image_elements = $news_element->elements($news_element->using('xpath')->value($xpath));
                $this->assertFalse((bool) count($image_elements));
            }

            if ($news_view_mode == 'detailed') {
                // Check that the full summary or text with 200 chars approximate
                // length is also displayed.
                $xpath = './/span[@class="summary"]';
                $text = $news_element->element($news_element->using('xpath')->value($xpath))->text();

                // Get rid of extra spaces due to new lines.
                $text = str_replace('  ', ' ', trim($text));

                if (!empty($news_item['summary'])) {
                    // The summary will be fully displayed even if longer than 200 characters.
                    $this->assertEquals(trim($news_item['summary']), $text);
                } else {
                    // The body will be cut to approximately 200 characters length and have ellipsis at the end.
                    $this->assertEquals('...', substr($text, -3, 3));
                    $text = substr($text, 0, -3);
                    $this->assertStringStartsWith($text, trim($news_item['body']));
                }
            }

            // We don't know the exact date and format, so just check that the
            // date div is present.
            $this->byXPath('//div[@data-news-item-nid="' . $nid . '"]//span[@class="date"]');
        }
    }
}
