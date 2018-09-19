<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\Pane\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LandingPageLayoutPage as LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for the menu structure pane that don't run on all content types.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuStructurePaneTest extends WebDriverTestCase
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var SearchPage
     */
    protected $searchPage;

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

        // Instantiate often used classes.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->searchPage = new SearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * A menu containing an mlid in its title should not be selected.
     *
     * Regression test for KANWEBS-2144.
     *
     * When Drupal constructs a menu tree, the resulting tree has the array keys
     * '{weight} {title} {mlid}'. KANWEBS-2144 reported an issue where the wrong
     * menu tree would be shown if a menu item contains the mlid of another menu
     * item in its title.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-2144
     *
     * @group menu
     * @group menuStructurePane
     * @group panes
     * @group regression
     */
    public function testMenuTitleContainingMlid()
    {
        // Create two basic pages and add them to the first level of the main
        // menu.
        $pages = array();
        for ($i = 0; $i < 2; $i++) {
            // Create the page. Append a question mark to verify that it also
            // works when the title contains a regular expression operator.
            // This is a regression test for KANWEBS-2258.
            $title = $this->alphanumericTestDataProvider->getValidValue(8) . '?';
            $nid = $this->contentCreationService->createBasicPage($title);
            $this->administrativeNodeViewPage->go($nid);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
            $this->editPage->checkArrival();

            // Add the page to the menu.
            $mlid = $this->editPage->addOrEditNodeMenuItem(null, $title, 'main_menu_nl');
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Publish the page.
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();

            $pages[] = array(
                'nid' => $nid,
                'mlid' => $mlid,
                'title' => $title,
            );
        }

        // Change the menu title of the first page so it contains the mlid of
        // the second page.
        $this->editPage->go($pages[0]['nid']);
        $menu_items = $this->editPage->nodeMenuItemList->getMenuItems();
        $title = $pages[1]['mlid'];
        $this->editPage->addOrEditNodeMenuItem($menu_items[$pages[0]['mlid']], $title);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Add a menu structure pane to the page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        $region = $this->layoutPage->display->getRandomRegion();
        $region->buttonAddPane->click();

        $menu_pane = new MenuStructurePanelsContentType($this);

        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($menu_pane);

        // Configure the menu pane to show the second page.
        $menu_pane->menu->selectOptionByValue('main_menu_nl');
        $menu_pane->menuItem->selectOptionByLabel('-' . $pages[1]['title']);
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check that the correct page is shown in the menu structure pane.
        $this->byXPath('//div[contains(@class, "pane-add-menu-structure")]//ul/li//*[text() = "' . $pages[1]['title'] . '"]');
    }

    /**
     * Tests the menu title pane top option of menu structure panes.
     */
    public function testMenuTitleOption()
    {
        // Add a menu item with one child. the link being '<front>'
        $menu_item_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $this->menuOverviewPage->go();
        $mlid = $this->menuOverviewPage->createMenuItem(array(
            'title' => $menu_item_title,
            'internal_link' => '<front>',
        ), array());
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();
        $child_menu_item_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $this->menuOverviewPage->createMenuItem(array(
            'title' => $child_menu_item_title,
            'internal_link' => '<front>',
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ':' . $mlid,
        ), array($mlid));
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();

        // Add a landing page and put a menus structure pane on it.
        $landing_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();

        $menu_pane = new MenuStructurePanelsContentType($this);

        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($menu_pane);

        // Configure the menu pane to show the the menu item.
        $menu_pane->menu->selectOptionByValue('main_menu_nl');
        $menu_pane->menuItem->selectOptionByLabel('-' . $menu_item_title);
        // Enable the top menu and pick the menu item title.
        $menu_pane->topSection->enable->check();
        $menu_pane->topSection->enable->waitUntilDisplayed();
        $menu_pane->topSection->contentTypeRadios->title->select();
        $menu_pane->topSection->urlTypeRadios->menuLink->select();
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));
        $uuid = $pane->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check if the pane exists.
        $xpath = '//div[@data-pane-uuid="' . $uuid . '"]';
        $this->waitUntilElementIsPresent($xpath);

        // check if the child is shown.
        $this->assertTextPresent($child_menu_item_title);

        // Check if the title is shown.
        $xpath .= '//div[contains(@class, "pane-section-top")]//a';
        $pane_top_link = $this->element($this->using('xpath')->value($xpath));
        $link_text = $pane_top_link->text();
        $this->assertEquals($menu_item_title, $link_text);
        $link_url = $pane_top_link->attribute('href');
        // We append the '/' since that is the base path.
        $language_prefix = '';
        if (MultilingualService::isMultilingual($this)) {
            $language_prefix = MultilingualService::getLanguagePathPrefix($this);
        }

        $this->assertEquals($this->base_url . ($language_prefix ? '/' . $language_prefix : '/'), $link_url);

        // Edit the menu item to an external link.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->editMenuItem($mlid, array(
            'external_link' => 'http://www.paddlecms.be',
        ));
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();


        $this->administrativeNodeViewPage->go($landing_nid);

        $pane_top_link = $this->element($this->using('xpath')->value($xpath));
        $link_text = $pane_top_link->text();
        $this->assertEquals($menu_item_title, $link_text);
        $link_url = $pane_top_link->attribute('href');
        $this->assertEquals('http://www.paddlecms.be/', $link_url);

        // Edit the menu item to an internal link from a basic page you have made.
        $title = $this->alphanumericTestDataProvider->getValidValue(12);
        $basic_page_nid = $this->contentCreationService->createBasicPage($title);

        $this->menuOverviewPage->go();
        $this->menuOverviewPage->editMenuItem($mlid, array(
            'internal_link' => 'node/' . $basic_page_nid,
        ));
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();


        $this->administrativeNodeViewPage->go($landing_nid);

        $pane_top_link = $this->element($this->using('xpath')->value($xpath));
        $link_text = $pane_top_link->text();
        $this->assertEquals($menu_item_title, $link_text);
        $link_url = $pane_top_link->attribute('href');

        $this->assertEquals($this->base_url . '/' . strtolower($title), $link_url);
    }
}
