<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FlyOutMenu\FlyOutMenuTest.
 */

namespace Kanooh\Paddle\App\FlyOutMenuTest;

use Kanooh\Paddle\Apps\FlyOutMenu;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Fly-ou Menu Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FlyOutMenuTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->frontPage = new FrontPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);

        $this->userSessionService->login('SiteManager');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new FlyOutMenu);

        // Create a new theme to enable flyOut menu style.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');

        // Set the menu style to flyOut menu.
        $this->themerEditPage->header->menuStyleOptions->flyOutMenu->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the login page and log in as chief editor.
        $this->userSessionService->switchUser('ChiefEditor');

        // Remove all menu items created by previous tests. If we have too many
        // menu items and the ones we are looking for are hidden, we can still
        // slide to find them but if the menu item if JUST visible the webdriver
        // will find it but mouseover will not bring the Megadropdown up.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->emptyMenu();
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
     * Tests the fly out menu.
     */
    public function testFlyOutMenu()
    {
        // Create 2 pages and publish them.
        $nid_1 = $this->contentCreationService->createBasicPage();
        $this->administrativeNodeViewPage->go($nid_1);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $nid_2 = $this->contentCreationService->createBasicPage();
        $this->administrativeNodeViewPage->go($nid_2);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Create 2 menu items linking to the nodes where the menu link of node
        // 2 is a child of menu link of node 1.
        $this->menuOverviewPage->go();
        $values = array(
            'title' => $this->alphanumericTestDataProvider->getValidValue(),
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":0",
            'internal_link' => "node/$nid_1",
        );
        $mlid_1 = $this->menuOverviewPage->createMenuItem($values);

        $values = array(
            'title' => $this->alphanumericTestDataProvider->getValidValue(),
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":$mlid_1",
            'internal_link' => "node/$nid_2",
        );
        $mlid_2 = $this->menuOverviewPage->createMenuItem($values, array($mlid_1));

        // Go to the front end and verify the fly out menu is rendered.
        $this->frontPage->go();
        $this->frontPage->mainMenuDisplay->isPresent();
        $this->assertTrue($this->frontPage->mainMenuDisplay->checkNextLevelPresentInMenuDisplayBlock($mlid_1, $mlid_2));
    }
}
