<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\MenuLanguageSyncTest.
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
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

        // Instantiate some classes to use in the test.
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests that a new menu is created when new language is enabled.
     */
    public function testNewMenusForNewLanguages()
    {
        // Go to the configuration page and enable 2 languages.
        MultilingualService::enableLanguages($this, array('Bulgarian', 'Irish'));

        // Go to the menu manager and check that the menus have been created.
        // Use the main menu.
        foreach (array('bg', 'ga') as $language) {
            $this->menuOverviewPage->go(MenuOverviewPage::MAIN_MENU_ID);
            $this->menuOverviewPage->languageSwitcher->switchLanguage($language);
            $this->menuOverviewPage->checkArrival();

            // Check the language of the menu.
            $this->assertEquals($language, $this->menuOverviewPage->overviewForm->getMenuLanguage());
        }
    }
}
