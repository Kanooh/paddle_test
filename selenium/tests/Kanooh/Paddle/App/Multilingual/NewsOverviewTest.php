<?php
/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\NewsOverviewTest.
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\NewsOverviewPageViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
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
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

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
     * @var NewsOverviewPageViewPage
     */
    protected $frontEndNewsOverviewPage;

    /**
     * @var ViewPage
     */
    protected $frontEndPaddlePage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontEndNewsOverviewPage = new NewsOverviewPageViewPage($this);
        $this->frontEndPaddlePage = new ViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the News app.
        $this->appService->enableApp(new News);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests news overview multilingual awareness.
     */
    public function testNewsOverviewMultilingual()
    {
        // Create an English and Dutch news item.
        $nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $nl_nid = $this->contentCreationService->createNewsItem($nl_title);
        $this->administrativeNodeViewPage->go($nl_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $en_title = $this->alphanumericTestDataProvider->getValidValue();
        $en_nid = $this->contentCreationService->createNewsItem($en_title);
        $this->contentCreationService->changeNodeLanguage($en_nid, 'en');
        $this->administrativeNodeViewPage->go($en_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontEndNewsOverviewPage->go();
        $this->assertTextPresent($nl_title);
        $this->assertTextNotPresent($en_title);

        $translations = translation_node_get_translations($this->frontEndNewsOverviewPage->getNodeId());
        $this->frontEndPaddlePage->go($translations['en']->nid);
        $this->assertTextPresent($en_title);
        $this->assertTextNotPresent($nl_title);
    }
}
