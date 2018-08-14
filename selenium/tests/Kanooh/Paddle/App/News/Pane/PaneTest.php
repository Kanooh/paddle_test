<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\Pane\PaneTest.
 */

namespace Kanooh\Paddle\App\News\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Pane\News\News as NewsPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\News\NewsPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\NewsPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the news pane.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var NewsPage
     */
    protected $editPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndViewPage
     */
    protected $frontendPage;

    /**
     * @var PanelsContentPage
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

        // Instantiate some service classes for later use.
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->editPage = new NewsPage($this);
        $this->frontendPage = new FrontEndViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Instantiate some common page classes for later use.
        $this->layoutPage = new PanelsContentPage($this);

        // Enable the carousel app if it's not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new News);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tear down method.
     */
    public function tearDown()
    {
        // Delete any assets created during the tests.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }

    /**
     * Tests the basic configuration and functionality of the pane.
     *
     * @group news
     * @group panes
     */
    public function testPane()
    {
        // Create an image assets with the test data.
        $image = $this->assetCreationService->createImage();

        // Create a news item.
        $node_title = $this->alphanumericTestDataProvider->getValidValue();
        $date = strtotime('now');
        $formatted_date = format_date($date, 'custom', 'l j F');
        $test_nid = $this->contentCreationService->createNewsItem($node_title);

        $this->editPage->go($test_nid);
        $this->editPage->featuredImage->selectAtom($image['id']);
        $this->editPage->creationDate->fill(date('d/m/Y', $date));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create a news pane.
        /** @var NewsPanelsContentType $content_type */
        $content_type = new NewsPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function ($modal) use ($content_type, $test_nid, $webdriver) {
                // Add a node.
                $content_type->getForm()->newsAutocompleteField->fill('node/' . $test_nid);
                $autocomplete = new AutoComplete($webdriver);
                $autocomplete->waitUntilDisplayed();
                $autocomplete->waitUntilSuggestionCountEquals(1);
                $autocomplete->pickSuggestionByPosition(0);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendPage->go($nid);

        // Test the pane.
        $news_pane = new NewsPane($this, $pane_uuid, $pane_xpath);
        $this->assertEquals($news_pane->title->text(), $node_title);
        $url = url('node/' . $test_nid);
        $this->assertContains($url, $news_pane->title->attribute('href'));
        $this->assertEquals($news_pane->date->text(), $formatted_date);
        $this->assertContains('sample_image', $news_pane->image->attribute('src'));
    }
}
