<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MegaDropDown\MegaDropDownTest.
 */

namespace Kanooh\Paddle\App\MegaDropdown;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\MegaDropdown;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePage;
// @todo Remove the next use once the creation of menu items goes through the Menu Manager UI.
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage\EditPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\Layout\Layout;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
// @todo Remove the next use once the creation of menu items goes through the Menu Manager UI.
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\MenuItemModal;
use Kanooh\Paddle\Pages\Node\EditPage\MenuItemPositionModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Mega Dropdown Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MegaDropdownTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The homepage.
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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
     * The Menu Overview page.
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * The configuration page for Mega Dropdown Paddlet class.
     * @var ConfigurePage
     */
    protected $megaDropdownConfigPage;

    /**
     * The Mega Dropdown Entity Edit page.
     * @var EditPage
     */
    protected $megaDropdownEditPage;

    /**
     * The random data generator.
     * @var Random
     */
    protected $random;

    /**
     * The layouts we expect to be able to use for the Mega Dropdown entities.
     * @var array
     */
    protected $expected_layouts = array(
        '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
    );

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

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->megaDropdownConfigPage = new ConfigurePage($this);
        $this->megaDropdownEditPage = new EditPage($this);
        $this->frontPage = new FrontPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);

        $this->random = new Random();

        // Go to the login page and log in as chief editor.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('SiteManager');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MegaDropdown);

        // Create a new theme to enable the Mega dropdown menu style.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Menu style');

        // Set the menu style to mega dropdown menu.
        $this->themerEditPage->header->menuStyleOptions->megaDropdown->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the login page and log in as chief editor.
        $this->userSessionService = new UserSessionService($this);
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
        // Delete all menu items at the end.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->emptyMenu();

        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * @group menu
     * @group modals
     * @group panes
     */
    public function testMegaDropdownEntitiesCreation()
    {
        // @todo Enable the paddlet using the UI.
        $add_content_page = new AddPage($this);
        $nodeEditPage = new NodeEditPage($this);

        $content_type = new CustomContentPanelsContentType($this);

        $menu_item_titles = array();
        foreach ($this->expected_layouts as $class_name) {
            // Add a menu item.
            // @todo To add new menu items through the Menu Manager UI we need the ModalDialog class from KANWEBS-1089.
            //          For the moment we can go through the "Add basic page" UI but this is not ideal.
            $add_content_page->go();
            $nid = $add_content_page->createNode('BasicPage');
            $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
            $nodeEditPage->checkArrival();

            $menu_item_title = $this->random->name(8);
            $menu_item_titles[] = $menu_item_title;

            $nodeEditPage->addToMenuLink->click();
            $modal = new MenuItemModal($this);
            $modal->waitUntilOpened();
            $modal->title->fill($menu_item_title);
            $modal->submit();
            $modal = new MenuItemPositionModal($this);
            $modal->waitUntilOpened();
            $modal->submit();
            $modal->waitUntilClosed();

            // Save to prevent an alert from popping up when we leave the page.
            $nodeEditPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->waitUntilTextIsPresent('has been updated.');
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

            $this->megaDropdownConfigPage->go();

            // Verify we find the menu item title.
            $entity_row = $this->megaDropdownConfigPage->table->getEntityRowByTitle($menu_item_title);
            $this->assertNotNull($entity_row);

            // Add a mega dropdown entity for this menu item.
            $create_link = $entity_row->linkCreate;

            $this->assertNotNull($create_link);
            $create_link->click();
            $this->waitUntilTextIsPresent('Choose layout');

            // Get all the layouts found in the modal.
            $layout_links = $this->megaDropdownConfigPage->createModal->getLayoutLinks();
            $this->assertEquals(count($layout_links), count($this->expected_layouts));

            /** @var Layout $layout */
            $layout = new $class_name($this);
            $layout_links[$layout->id()]->click();

            $this->waitUntilTextIsPresent('Mega Dropdown for ' . $menu_item_title);

            // Verify we have the layout that we selected.
            $current_layout = $this->megaDropdownEditPage->display->getCurrentLayoutId();
            $this->assertEquals($layout->id(), $current_layout);

            // Verify that the change layout modal has the correct number of layouts.
            $this->megaDropdownEditPage->contextualToolbar->buttonChangeLayout->click();
            $this->megaDropdownEditPage->layoutModal->waitUntilOpened();
            $layouts = $this->megaDropdownEditPage->layoutModal->getLayoutLinks();
            $this->assertEquals(count($this->expected_layouts), count($layouts));
            $this->megaDropdownEditPage->layoutModal->close();

            // Add a pane to a random region.
            $pane_body = $this->random->name(20);
            $content_type->body = $pane_body;
            $region = $this->megaDropdownEditPage->display->getRandomRegion();
            $region_id = $region->id();
            $this->megaDropdownEditPage->display->waitUntilEditorIsLoaded();
            $region->addPane($content_type);
            $this->waitUntilTextIsPresent($pane_body);
            $this->megaDropdownEditPage->contextualToolbar->buttonBack->click();

            // Confirm we want to leave without saving.
            $this->acceptAlert();
            $this->waitUntilTextIsPresent('Configure Mega Dropdown');

            // Edit again and verify the pane was not saved.
            $this->megaDropdownConfigPage->checkPath();
            $entity_row = $this->megaDropdownConfigPage->table->getEntityRowByTitle($menu_item_title);
            $entity_row->linkEdit->click();
            $this->megaDropdownEditPage->checkPath();
            $this->waitUntilTextIsPresent('Mega Dropdown for ' . $menu_item_title);
            $this->assertCount(0, $this->megaDropdownEditPage->display->region($region_id)->getPanes());

            // Add a new pane and this time save.
            $this->megaDropdownEditPage->display->waitUntilEditorIsLoaded();
            $content_type->body = $pane_body;
            $this->megaDropdownEditPage->display->getRandomRegion()->addPane($content_type);
            $this->waitUntilTextIsPresent($pane_body);
            $this->megaDropdownEditPage->contextualToolbar->buttonSave->click();
            $this->waitUntilTextIsPresent('Configure Mega Dropdown');

            // Go to the homepage to check if Mega Dropdown is visible with the
            // new pane.
            $this->frontPage->go();
            $menu_item_link = $this->frontPage->mainMenuDisplay->getMenuItemLinkByTitle($menu_item_title);
            $this->assertFalse($this->isTextPresent($pane_body));
            $this->textPresentInMegaDropdown($menu_item_link, $pane_body);

            // Go back to the Mega Dropdown configuration page and edit the mega dropdown entity.
            $this->megaDropdownConfigPage->go();
            $entity_row->linkEdit->click();
            $this->waitUntilTextIsPresent($pane_body);

            // Add another pane.
            $second_pane_body = $this->random->name(20);
            $content_type->body = $second_pane_body;
            $this->megaDropdownEditPage->display->waitUntilEditorIsLoaded();
            $this->megaDropdownEditPage->display->getRandomRegion()->addPane($content_type);
            $this->waitUntilTextIsPresent($pane_body);
            $this->megaDropdownEditPage->contextualToolbar->buttonSave->click();
            $this->waitUntilTextIsPresent('Configure Mega Dropdown');

            // Go to the front-end to check that both panes are there.
            $this->frontPage->go();
            $menu_item_link = $this->frontPage->mainMenuDisplay->getMenuItemLinkByTitle($menu_item_title);
            $this->assertFalse($this->isTextPresent($pane_body));
            $this->assertFalse($this->isTextPresent($second_pane_body));
            $this->textPresentInMegaDropdown($menu_item_link, $pane_body);
            $this->textPresentInMegaDropdown($menu_item_link, $second_pane_body);
        }

        // Remove all the mega dropdown entities created.
        foreach ($menu_item_titles as $menu_item_title) {
            $this->megaDropdownConfigPage->go();
            $entity_row = $this->megaDropdownConfigPage->table->getEntityRowByTitle($menu_item_title);
            $entity_row->linkDelete->click();

            // Close the "Confirm delete" dialog without deleting the entity.
            $this->waitUntilTextIsPresent('Delete Mega Dropdown');
            $this->megaDropdownConfigPage->confirmModal->waitUntilOpened();
            $this->megaDropdownConfigPage->confirmModal->close();

            // Verify we are back on the Mega dropdown configuration page, the entity is still there and click "Delete".
            $this->megaDropdownConfigPage->checkPath();
            $entity_row = $this->megaDropdownConfigPage->table->getEntityRowByTitle($menu_item_title);
            $entity_row->linkDelete->click();

            // This time really delete the entity.
            $this->waitUntilTextIsPresent('Delete Mega Dropdown');
            $this->megaDropdownConfigPage->confirmModal->waitUntilOpened();
            $this->megaDropdownConfigPage->confirmModal->confirm();
            $this->waitUntilTextIsPresent('The Mega Dropdown you selected has been deleted');
            $this->megaDropdownConfigPage->checkPath();
        }
    }

    /**
     * Checks if a certain text is present in the front-end rendering of a Mega Dropdown.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $menu_item_link
     *   The menu item link object under which the Mega Dropdown is displayed.
     *
     * @param string $text
     *   The text for which we search.
     */
    public function textPresentInMegaDropdown($menu_item_link, $text)
    {
        $this->moveto($menu_item_link);
        $xpath = '//div[contains(@class,"paddle-mega-dropdown")]//*[text()="' . $text . '"]';

        $mdd_pane = $this->waitUntilElementIsDisplayed($xpath);

        // Move to the mega dropdown entity itself - it should remain visible.
        $this->moveto($mdd_pane);
        $this->assertTrue($mdd_pane->displayed());
    }
}
