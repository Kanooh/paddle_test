<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\MultilingualMenuItemsTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the menu items when the multilingual paddlet is enabled.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MultilingualMenuItemsTest extends WebDriverTestCase
{
    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The homepage.
     *
     * @var FrontPage
     */
    protected $frontPage;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontPage = new FrontPage($this);
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
     * Tests that menu items don't get "(disabled)" label on the back-end while
     * remaining visible on the front-end.
     */
    public function testMultilingualMenuItems()
    {
        // Go to the Menu Manager page and switch to German.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->languageSwitcher->switchLanguage('de');
        $this->menuOverviewPage->checkArrival();

        // Now create a menu item and check that the menu item doesn't get a
        // "(disabled)" label.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->menuOverviewPage->createMenuItem(array('title' => $title));

        $row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByTitle($title);
        $this->assertFalse($row->menuItemDisabled());

        // Now go to the front-end and make sure the menu item is displayed.
        $this->menuOverviewPage->siteSettingsMenuBlock->links->linkSiteName->click();
        $this->frontPage->checkArrival();
        $this->assertNotNull($this->frontPage->mainMenuDisplay->getMenuItemLinkByTitle($title));
    }
}
