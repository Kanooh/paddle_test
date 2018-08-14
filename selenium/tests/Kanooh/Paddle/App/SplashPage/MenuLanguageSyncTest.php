<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SplashPage\MenuLanguageSyncTest.
 */

namespace Kanooh\Paddle\App\SplashPage;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Apps\SplashPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\LanguageSelectionPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on creating new menus for newly enabled languages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuLanguageSyncTest extends WebDriverTestCase
{
    /**
     * App service.
     *
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
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

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

        $this->frontPage = new FrontPage($this);
        $this->languageSelectionPage = new LanguageSelectionPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
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
    }

    /**
     * Tests the menu language is properly shown after arriving on the front page.
     *
     * This test has been added since KANWEBS-5902 because there was a bug
     * with the menu language staying on the default language after having
     * chosen a language with the language selection page and browsing back
     * to the base_url.
     */
    public function testMenuLanguageAfterLanguageSelection()
    {
        // Create a menu item in the default language.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->createMenuItem(array('title' => 'Default title'));

        // Switch to French.
        $this->menuOverviewPage->languageSwitcher->switchLanguage('fr');
        $this->menuOverviewPage->checkArrival();

        // Now create a French menu item.
        $this->menuOverviewPage->createMenuItem(array('title' => 'Titre français'));

        // Enable the Splash Page module.
        $this->appService->enableApp(new SplashPage);
        $this->userSessionService->clearCookies();
        $this->url($this->base_url);

        // Assert that you get redirected to the splash page.
        $this->languageSelectionPage->waitUntilPageIsLoaded();
        $this->byCssSelector('body.page-language-selection');

        // Click on the French link.
        $this->languageSelectionPage->languageSelection->link('french')->click();

        // Assert that you arrive on the Front page.
        $this->frontPage->checkArrival();

        // Go back to the base URL.
        $this->url($this->base_url);

        // Assert that you are on the French page.
        $language = $this->frontPage->languageSwitcher->getActiveLanguage();
        $this->assertEquals('fr', $language);

        // Assert that the French menu item is shown.
        $this->assertTextPresent('Titre français');

        // Assert that the default menu item is not shown.
        $this->assertTextNotPresent('Default title');
    }
}
