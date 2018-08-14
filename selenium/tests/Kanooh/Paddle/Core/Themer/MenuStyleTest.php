<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\MenuStyleTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Apps\FlyOutMenu;
use Kanooh\Paddle\Apps\MegaDropdown;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePage as MegaDropdownConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage\EditPage as MegaDropdownEditPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test all themer menu styles.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuStyleTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The homepage.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The 'Add' page of the Paddle Themer module.
     *
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * The 'Edit' page of the Paddle Themer module.
     *
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The configuration page for Mega Dropdown Paddlet class.
     *
     * @var MegaDropdownConfigurePage
     */
    protected $megaDropdownConfigPage;

    /**
     * The Mega Dropdown Entity Edit page.
     *
     * @var MegaDropdownEditPage
     */
    protected $megaDropdownEditPage;

    /**
     * The Menu Overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

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

        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontPage = new FrontPage($this);
        $this->megaDropdownConfigPage = new MegaDropdownConfigurePage($this);
        $this->megaDropdownEditPage = new MegaDropdownEditPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MegaDropdown);
        $this->appService->enableApp(new FlyOutMenu);
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
     * Tests the menu styles.
     *
     * @group themer
     */
    public function testMenuStyles()
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
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $values = array(
            'title' => $title,
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

        // Create a mega dropdown for the parent menu item.
        $content_type = new CustomContentPanelsContentType($this);
        $this->megaDropdownConfigPage->go();
        $this->megaDropdownConfigPage->checkPath();
        $this->waitUntilTextIsPresent($title);
        $entity_row = $this->megaDropdownConfigPage->table->getEntityRowByTitle($title);
        $entity_row->linkCreate->click();
        $this->waitUntilTextIsPresent('Choose layout');
        $layout_links = $this->megaDropdownConfigPage->createModal->getLayoutLinks();
        /** @var Paddle2Col6to6Layout $layout */
        $layout = new Paddle2Col6to6Layout($this);
        $layout_links[$layout->id()]->click();
        $this->megaDropdownEditPage->checkPath();
        $this->waitUntilTextIsPresent('Mega Dropdown for ' . $title);

        // Add a new pane to the mega dropdown.
        $this->megaDropdownEditPage->display->waitUntilEditorIsLoaded();
        $pane_body = $this->alphanumericTestDataProvider->getValidValue();
        $content_type->body = $pane_body;
        $this->megaDropdownEditPage->display->getRandomRegion()->addPane($content_type);
        $this->waitUntilTextIsPresent($pane_body);
        $this->megaDropdownEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Configure Mega Dropdown');

        // Create a new theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');

        // Check that the no menu style option is selected by default.
        $this->assertEquals(
            $this->themerEditPage->header->menuStyleOptions->getSelected()->getValue(),
            $this->themerEditPage->header->menuStyleOptions->noMenuStyle->getValue()
        );

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify no menu style is set.
        $this->assertFalse($this->frontPage->checkFlyOutMenuPresent());
        $this->assertFalse($this->frontPage->checkMegaDropdownMenuPresent());

        // Set the menu style to fly-out menu.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');
        $this->themerEditPage->header->menuStyleOptions->flyOutMenu->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the fly-out menu style is set.
        $this->assertTrue($this->frontPage->checkFlyOutMenuPresent());
        $this->assertFalse($this->frontPage->checkMegaDropdownMenuPresent());

        // Check that the fly-out menu style option is selected.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');
        $this->assertEquals(
            $this->themerEditPage->header->menuStyleOptions->getSelected()->getValue(),
            $this->themerEditPage->header->menuStyleOptions->flyOutMenu->getValue()
        );

        // Set the menu style to mega dropdown menu.
        $this->themerEditPage->header->menuStyleOptions->megaDropdown->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the front page.
        $this->frontPage->go();

        // Verify the mega dropdown menu style is set.
        $this->assertFalse($this->frontPage->checkFlyOutMenuPresent());
        $this->assertTrue($this->frontPage->checkMegaDropdownMenuPresent());

        // Check that the mega dropdown menu style option is selected.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');
        $this->assertEquals(
            $this->themerEditPage->header->menuStyleOptions->getSelected()->getValue(),
            $this->themerEditPage->header->menuStyleOptions->megaDropdown->getValue()
        );
    }
}
