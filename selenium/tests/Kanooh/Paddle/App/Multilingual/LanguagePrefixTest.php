<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\LanguagePrefixTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the language prefix persists troughout the website.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LanguagePrefixTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphaNumericTestDataProvider
     */
    protected $alphaNumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var TranslatePage
     */
    protected $translatePage;

    /**
     * User session service.
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

        // Instantiate some classes to use in the test.
        $this->alphaNumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->contentManagerPage = new ContentManagerPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->translatePage = new TranslatePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests the language prefix persists throughout the website.
     */
    public function testLanguagePrefix()
    {
        // Log out and login again. This is needed when the paddlet just got
        // installed, as the language prefix won't be there yet.
        $this->userSessionService->logout();
        $this->userSessionService->login('ChiefEditor');

        // Check that the language prefix is equal to the default language
        // immediately after logging in.
        $this->dashboardPage->checkArrival();
        $this->assertEquals('nl', MultilingualService::getLanguagePathPrefix($this));

        // Go to a non-node page to check that the prefix persist.
        $this->dashboardPage->adminMenuLinks->linkContent->click();
        $this->contentManagerPage->checkArrival();
        $this->assertEquals('nl', MultilingualService::getLanguagePathPrefix($this));

        // Create a Dutch node and an English translation for it.
        $nl_nid = $this->contentCreationService->createBasicPage();
        $en_nid = $this->contentCreationService->createBasicPage();
        $this->contentCreationService->changeNodeLanguage($en_nid, 'en');
        $this->administrativeNodeViewPage->go($nl_nid);

        // Make sure after a call to go() we are still with the Dutch prefix.
        $this->assertEquals('nl', MultilingualService::getLanguagePathPrefix($this));
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // We now arrive on the front-end. The language prefix should still be Dutch.
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertEquals('nl', MultilingualService::getLanguagePathPrefix($this));

        // Add a translation for this node.
        $this->translatePage->go($nl_nid);
        $en_title = $this->alphaNumericTestDataProvider->getValidValue();
        $this->translatePage->translationTable->getRowByLanguage('en')->translationLink->click();
        $modal = new CreateNodeModal($this);
        $modal->waitUntilOpened();
        $modal->title->fill($en_title);
        $modal->submit();
        $modal->waitUntilClosed();

        // We should now arrive on the English page's admin node view with the
        // English prefix.
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertEquals('en', MultilingualService::getLanguagePathPrefix($this));
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // We now arrive on the front-end. The language prefix should still be English.
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertEquals('en', MultilingualService::getLanguagePathPrefix($this));

        // Now use the language switcher to go the original Dutch node.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('nl');
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertEquals('nl', MultilingualService::getLanguagePathPrefix($this));

        // Set the Dutch node as homepage.
        variable_set('site_frontpage', 'node/' . $nl_nid);

        // Go directly to the English node.
        $this->url('en');
        $this->assertTextPresent($en_title);
    }
}
