<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SplashPage\SplashPageTest.
 */

namespace Kanooh\Paddle\App\SplashPageTest;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Apps\SplashPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\LanguageSelectionPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class SplashPageTest
 * @package Kanooh\Paddle\App\SplashPage
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SplashPageTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var LanguageSelectionPage
     */
    protected $languageSelectionPage;

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

        $this->frontPage = new FrontPage($this);
        $this->languageSelectionPage = new LanguageSelectionPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Disable Splash Page module.
        $app = new SplashPage;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Enable the Multilingual paddlet.
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        $this->userSessionService->login('ChiefEditor');
        MultilingualService::setPaddleTestDefaults($this);
        $this->userSessionService->logout();
    }

    /**
     * Tests the Splash page functionality.
     */
    public function testSplashPage()
    {
        // Browse to the Home page.
        $this->frontPage->go();
        // Assert that you did not get redirected to the splash page.
        try {
            $this->languageSelectionPage->waitUntilPageIsLoaded();
            $this->byCssSelector('body.page-language-selection');
            $this->fail('You should not arrive at the Splash page.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Enable the Splash Page module.
        $this->appService->enableApp(new SplashPage);

        $this->url($this->base_url);

        // Assert that you get redirected to the splash page.
        $this->languageSelectionPage->waitUntilPageIsLoaded();
        $this->byCssSelector('body.page-language-selection');

        // Assert that all required language links are shown.
        $this->languageSelectionPage->languageSelection->checkLinks(array('dutch', 'english', 'french'));

        // Click on the Dutch link.
        $this->languageSelectionPage->languageSelection->link('dutch')->click();

        // Assert that you arrive on the Front page.
        $this->frontPage->checkArrival();

        // Assert that you are on the Dutch page.
        $language = $this->frontPage->languageSwitcher->getActiveLanguage();
        $this->assertEquals('nl', $language);
    }
}
