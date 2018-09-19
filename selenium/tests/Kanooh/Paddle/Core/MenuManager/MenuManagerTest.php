<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\MenuManager\MenuManagerTest.
 */

namespace Kanooh\Paddle\Core\MenuManager;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;
use Kanooh\Paddle\Utilities\AjaxService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;

/**
 * Test the Paddle Menu Manager UI.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuManagerTest extends WebDriverTestCase
{
    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;

    /**
     * The administation dashboard page.
     *
     * @var DashboardPage $dashboardPage
     */
    protected $dashboardPage;

    /**
     * The admin 'View' page.
     *
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->dashboardPage = new DashboardPage($this);
        $this->adminNodeViewPage = new ViewPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->random = new Random();

        $drupal = new DrupalService();
        $drupal->bootstrap($this);
        // Login as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the Paddle Menu Manager UI.
     *
     * @group menu
     */
    public function testMenuManagerUI()
    {
        // Go to the Menu Manager overview page.
        $this->dashboardPage->checkArrival();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();
        $this->menuOverviewPage->checkArrival();

        // Create 3 menu titles - the first 2 for the 2 menus we are going to
        // create and the third to edit the second menu.
        $menu_titles = array();
        for ($i = 0; $i < 3; $i++) {
            $menu_titles[] = $this->random->name(8);
        }

        // Try first to create 2 custom menus.
        for ($i = 0; $i < 2; $i++) {
            $tsid = $this->menuOverviewPage->createCustomMenu(array('title' => $menu_titles[$i]));
            $this->assertNotNull($tsid);
        }

        // Now try to delete the first one.
        $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_titles[0])->click();
        $this->menuOverviewPage->checkArrival();
        $this->menuOverviewPage->deleteCustomMenu($menu_titles[0]);

        // Verify it's gone.
        $this->menuOverviewPage->checkArrival();
        $this->assertNull($this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_titles[0]));

        // Now edit the other one.
        $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_titles[1])->click();
        $menu_title = $menu_titles[2];
        $new_values = array(
            'title' => $menu_title,
        );
        $this->menuOverviewPage->editCustomMenu($new_values);

        // Make sure we have the title updated.
        $this->menuOverviewPage->checkArrival();
        $this->assertNotNull($this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_title));

        // Create some menu items.
        $number_items_per_level = 3;
        $starting_items = $this->menuOverviewPage->overviewForm->overviewFormTable->getNumberOfRows();
        // Remove the empty row from the calculations.
        if ($this->isTextPresent('There are no menu links yet.')) {
            $starting_items--;
        }

        // Get the machine menu name. We can do this from the <select> on the
        // "Create menu item" modal.
        $machine_name = $this->menuOverviewPage->getMenuName($menu_title);

        $menu_items = array();
        for ($level = 0; $level < 3; $level++) {
            for ($i = 0; $i < $number_items_per_level; $i++) {
                $values = array(
                    'title' => $this->random->name(8),
                    'description' => $this->random->string(20),
                );

                $parent = 0;
                $parents = array();
                if ($level > 0) {
                    // Put the item as a child of one of the items of the
                    // previous level.
                    $parent = array_rand($menu_items[$level - 1]);
                    $values['parent'] = $machine_name . ":$parent";

                    // Get all its parents.
                    $parents = $this->getMenuItemParentsMlids($menu_items, $parent, $level);
                }
                $mlid = $this->menuOverviewPage->createMenuItem($values, $parents);
                if ($mlid) {
                    $menu_items[$level][$mlid] = array(
                        'parents' => $parents,
                        'parent' => $parent ?: $mlid,
                        'title' => $values['title'],
                    );
                }
            }
        }

        // Reload the page.
        $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_title);

        // Check the editing of menu items.
        foreach ($menu_items as $level => $items) {
            foreach ($items as $mlid => $item) {
                if ($level > 0) {
                    // Make sure the menu item is there.
                    $this->menuOverviewPage->overviewForm->openTreeToMenuItem($item['parents'], $item['title']);
                }

                // Change the title and description.
                $new_values = array(
                    'title' => $this->random->name(255),
                    'description' => $this->random->string(20),
                );
                $menu_items[$level][$mlid]['title'] = $new_values['title'];
                $this->menuOverviewPage->editMenuItem($mlid, $new_values);
                $this->menuOverviewPage->waitUntilPageIsLoaded();

                // Now check if we can find the item by the new title.
                $this->menuOverviewPage->overviewForm->openTreeToMenuItem($item['parents'], $new_values['title']);
                $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_title);
            }
        }

        // Reload the page.
        $this->menuOverviewPage->go(array($tsid));

        // Check the number of menu items visible. Only the level 1 items should be
        // visible.
        $expected_items = $number_items_per_level + $starting_items;
        $current_number_items = $this->menuOverviewPage->overviewForm->overviewFormTable->getNumberOfRows();
        $this->assertEquals($expected_items, $current_number_items);

        // Clean up - remove all menu items.
        foreach ($menu_items as $level => $items) {
            foreach ($items as $mlid => $value) {
                $this->menuOverviewPage->deleteMenuItem($mlid);
                $this->menuOverviewPage->waitUntilPageIsLoaded();
            }
        }
        // We should have none of the menu links we created.
        $current_number_items = $this->menuOverviewPage->overviewForm->overviewFormTable->getNumberOfRows();
        if ($this->isTextPresent('There are no menu links yet.')) {
            $current_number_items--;
        }
        $this->assertEquals($starting_items, $current_number_items);

        // Finally delete the last menu we created.
        $this->menuOverviewPage->deleteCustomMenu($menu_title);

        // Make sure the path to the menu has been removed.
        $this->menuOverviewPage->go(array($tsid));
        $this->assertEquals(url('admin/structure/menu_manager/1', array('absolute' => true)), $this->url());
        $this->assertNull($this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_title));
    }

    /**
     * Generates an array with all the parent menu items (their mlids) of the
     * passed menu items.
     * @param  array $menu_items
     *   The menu items structure.
     * @param  int $parent
     *   The mlid of the parent item.
     * @param  int $level
     *   The depth on which the current item will be.
     *
     * @return array
     *   Mlids of the parent menu items.
     */
    public function getMenuItemParentsMlids($menu_items, $parent, $level)
    {
        $parents = array();

        $grandparent = $parent;
        while ($level > 1) {
            $level--;
            $grandparent = $menu_items[$level][$grandparent]['parent'];
            $parents[] = $grandparent;
        }
        // The parents should be ordered in such a way so that the deepest parent
        // is last in the array.
        $parents = array_reverse($parents);
        $parents[] = $parent;

        return array_unique($parents);
    }

    /**
     * Tests the issue described in https://one-agency.atlassian.net/browse/KANWEBS-1790.
     *
     * @group regression
     */
    public function testDoubleChangeMessage()
    {

        // Create 2 menu items on level 1.
        $mlids = array();
        for ($i = 0; $i < 2; $i++) {
            $menu_item = array(
                'link_path' => '<front>',
                'link_title' => $this->random->name(8),
                'menu_name' => MenuOverviewPage::MAIN_MENU_NAME,
                'language' => MenuOverviewPage::MAIN_MENU_LANGUAGE,
            );
            $mlids[] = menu_link_save($menu_item);
        }

        // Add a menu item below the second level 1 item.
        $child_title = $this->random->name(8);
        $menu_item = array(
                'link_path' => '<front>',
                'link_title' => $child_title,
                'menu_name' => MenuOverviewPage::MAIN_MENU_NAME,
                'language' => MenuOverviewPage::MAIN_MENU_LANGUAGE,
                'plid' => $mlids[0],
            );
        $mlids[] = menu_link_save($menu_item);

        // Create another 2 menu items on level 1.
        for ($i = 0; $i < 2; $i++) {
            $menu_item = array(
                'link_path' => '<front>',
                'link_title' => $this->random->name(8),
                'menu_name' => MenuOverviewPage::MAIN_MENU_NAME,
                'language' => MenuOverviewPage::MAIN_MENU_LANGUAGE,
            );
            $mlids[] = menu_link_save($menu_item);
        }
        $this->menuOverviewPage->go();

        // Drag a menu item around.
        $row_1 = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByMlid($mlids[4]);
        $this->menuOverviewPage->overviewForm->overviewFormTable->changeMenuItemPosition($row_1, 2);
        $row_1->linkTableDrag->click();

        // Drag it again to be sure things changed.
        $this->menuOverviewPage->overviewForm->overviewFormTable->changeMenuItemPosition($row_1, 1);

        // Lose focus to trigger the "Change" message.
        $row_1->linkTableDrag->click();
        $this->waitUntilTextIsPresent('Changes made in this table will not be saved until the form is submitted.');

        // Open the subtree.
        $this->menuOverviewPage->overviewForm->openTreeToMenuItem(array($mlids[0]), $child_title);

        // Drag the menu item around again.
        $row_1 = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByMlid($mlids[4]);

        $this->menuOverviewPage->overviewForm->overviewFormTable->changeMenuItemPosition($row_1, 1);

        // Lose focus to trigger the "Change" message.
        $row_1->linkTableDrag->click();
        $this->assertTextPresent('Changes made in this table will not be saved until the form is submitted.');
        // Verify the text is only shown once.
        $elements = $this->elements($this->using('xpath')->value('//div[contains(@class, "tabledrag-changed-warning")]'));
        $this->assertEquals(1, count($elements));

        // Clean up.
        foreach ($mlids as $mlid) {
            $this->menuOverviewPage->deleteMenuItem($mlid);
        }
    }

    /**
     * Tests the issue described in https://one-agency.atlassian.net/browse/KANWEBS-2315.
     *
     * @group regression
     * @group menu
     */
    public function testMenuItemMenuChanged()
    {
        // Go to the Menu manager page.
        $this->dashboardPage->checkArrival();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();
        $this->menuOverviewPage->checkArrival();

        // Make sure we land on the "Main menu" edit page.
        $args = $this->menuOverviewPage->getPathArguments();
        $this->assertEquals(1, $args[0]);

        // Add a menu item to the "Main menu".
        $menu_item_title = 'blabla';
        $mlid = $this->menuOverviewPage->createMenuItem(array('title' => $menu_item_title), array());

        // Add new menu for later use.
        $menu_title = $this->random->name(8);
        $this->menuOverviewPage->createCustomMenu(array('title' => $menu_title));

        // Get the machine name of the new menu.
        $link = $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle($menu_title);
        $link->click();
        $this->menuOverviewPage->checkArrival();
        $menu_name = $this->menuOverviewPage->getMenuName();

        // Add a basic page and a menu pane to it with the menu item set to it.
        $content_service = new ContentCreationService($this, $this->userSessionService);
        $nid = $content_service->createBasicPage($this->random->name(8));
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $layout_page = new LayoutPage($this);
        $layout_page->checkArrival();

        $region = $layout_page->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();

        $menu_content_type = new MenuStructurePanelsContentType($this);

        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($menu_content_type);

        // Configure the menu pane to show menu item we created earlier.
        $menu_content_type->menu->selectOptionByValue('main_menu_nl');
        $menu_content_type->menuItem->selectOptionByValue($mlid);
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        // Get the newly created pane.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane = current(array_diff_key($panes_after, $panes_before));

        $layout_page->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Check that the pane contains the menu item.
        $this->assertTextPresent($menu_item_title);

        // Change the menu of the menu item.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->editMenuItem($mlid, array('parent' => "$menu_name:0"));
        $this->menuOverviewPage->checkArrival();

        // Check that the pane still contains the menu item.
        $this->adminNodeViewPage->go($nid);
        $this->assertTextPresent($menu_item_title);

        // Make sure the pane edit form is pointing to the new menu of the menu
        // item so the menu item is selected in the "Menu item" dropdown.
        $this->adminNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $layout_page->checkArrival();
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $this->assertEquals($menu_name, $menu_content_type->menu->getSelectedValue());
        $this->assertEquals($mlid, $menu_content_type->menuItem->getSelectedValue());

        // Close modal and save page, to prevent alert boxes from popping up
        // after this.
        $pane->editPaneModal->close();
        $pane->editPaneModal->waitUntilClosed();
        $layout_page->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests the issue described in http://one-agency.atlassian.net/browse/KANWEBS-2423.
     *
     * @group regression
     * @group menu
     */
    public function testInfinitelyIncreasingWeight()
    {
        // Create a parent item.
        $menu_item = array(
            'link_path' => '<front>',
            'link_title' => $this->random->name(8),
            'menu_name' => MenuOverviewPage::MAIN_MENU_NAME,
            'language' => MenuOverviewPage::MAIN_MENU_LANGUAGE,
        );
        $parent_mlid = menu_link_save($menu_item);

        // Create > 100 menu items.
        $titles = array();
        for ($i = 0; $i < 111; $i++) {
            // Add the index to the title to make it unique.
            $titles[] = $this->random->name(13) . $i;
            $menu_item = array(
                'link_path' => '<front>',
                'link_title' => end($titles),
                'menu_name' => MenuOverviewPage::MAIN_MENU_NAME,
                'language' => MenuOverviewPage::MAIN_MENU_LANGUAGE,
                'plid' => $parent_mlid,
            );
            menu_link_save($menu_item);
        }

        // Sort the titles to get them in the order Drupal will order them.
        sort($titles);

        // Go to the Menu Overview Page and expand the parent item.
        $this->menuOverviewPage->go();
        $target_row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByMlid($parent_mlid);
        $target_row->linkShowChildItems->click();
        $target_row->waitUntilChildItemsArePresent();

        // Drag the last 2 menu items to first position.
        for ($i = 0; $i < 2; $i++) {
            $title = array_pop($titles);
            $row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByTitle($title);

            // The weight ot the item we will move is 0 before any reordering
            // and greater than 0 afterwords.
            if ($i == 0) {
                $this->assertEquals(0, $row->menuItemWeight->value());
            } else {
                $this->assertTrue($row->menuItemWeight->value() > 0);
            }

            $this->menuOverviewPage->overviewForm->overviewFormTable->changeMenuItemPosition($row, 0, $parent_mlid);

            // Check that the weight start from 0.
            $this->menuOverviewPage->overviewForm->showWeightsToggleLink->click();
            $row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByTitle($title);
            $this->assertEquals(0, $row->menuItemWeight->value());

            // Save the change.
            $this->menuOverviewPage->contextualToolbar->buttonSave->click();
            $this->menuOverviewPage->checkArrival();

            // Open the parent.
            $this->menuOverviewPage->overviewForm->openTreeToMenuItem(array($parent_mlid), $title);

            // Check the weight of the first item is 0.
            $row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByTitle($title);
            $this->assertEquals(0, $row->menuItemWeight->value());

            // Restore to tabledrag.
            $this->menuOverviewPage->overviewForm->showWeightsToggleLink->click();
        }
    }

    /**
     * The backdrop should prevent clicking underlying elements.
     *
     * The speed at which the backdrop closes when closing the modal is
     * unpredictable. Therefor, we can not reliably assert that the page
     * elements on the underlying page are not clickable while the backdrop
     * is closing.
     * We only test that while the modal is still open.
     *
     * @group regression
     * @group menu
     */
    public function testLockBackdrop()
    {
        if ($this->getBrowser() != 'chrome') {
            $this->markTestSkipped('Only executing this test with Chrome until https://code.google.com/p/selenium/issues/detail?id=5142 is fixed.');
        }

        $this->menuOverviewPage->go();

        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();

        try {
            $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
            $this->fail('The element was clickable while the backdrop should have prevented it.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->assertContains('not clickable', $e->getMessage());
        }
    }

    /**
     * Tests that menu items to unpublished pages still appear in the parent
     * dropdown on the menu item edit/create page.
     */
    public function testUnpublishedNodesInMenuItemCreationDialog()
    {
        // Create an unpublished page to use in the menu.
        $nid = $this->contentCreationService->createBasicPage();

        $this->menuOverviewPage->go();

        // Add a menu item for the node to the main menu.
        $values = array(
            'title' => $this->alphanumericDataProvider->getValidValue(),
            'internal_link' => "node/$nid",
        );
        $mlid = $this->menuOverviewPage->createMenuItem($values);

        // Now make sure that although it's not published the menu item can
        // still be selected as a parent.
        $values = array(
          'title' => $this->alphanumericDataProvider->getValidValue(),
          'parent' => "main_menu_nl:$mlid",
        );
        // This will fail if the parent is not present in the dropdown.
        $this->menuOverviewPage->createMenuItem($values, array($mlid));
    }

    /**
     * Tests that long titles of menu items are fully displayed in the parent
     * dropdown on the menu item edit/create page.
     */
    public function testLongTitlesInMenuItemCreationDialog()
    {
        $title = $this->alphanumericDataProvider->getValidValue(80);
        $this->contentCreationService->createBasicPage($title);
        // Go to the Menu Manager overview page.
        $this->dashboardPage->checkArrival();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();
        $this->menuOverviewPage->checkArrival();

        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($title);

        // Now check that the long title is fully found in the dropdown.
        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();
        $modal->createMenuItemForm->title->fill($this->alphanumericDataProvider->getValidValue());
        $label = "--$title (unpublished)";
        $modal->createMenuItemForm->navigation->selectOptionByLabel($label);

        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Tests the automatic menu title addition.
     *
     * @group menu
     */
    public function testMenuAutoTitle()
    {
        $title = $this->alphanumericDataProvider->getValidValue(5);
        $this->contentCreationService->createBasicPage($title);
        // Go to the Menu Manager overview page.
        $this->dashboardPage->checkArrival();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();
        $this->menuOverviewPage->checkArrival();

        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($title);

        $this->menuOverviewPage->checkArrival();
        $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByTitle($title)->linkEditMenuItem->click();
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();
        $this->assertEquals($title, $modal->createMenuItemForm->title->getContent());
        $modal->submit();
    }

    /**
     * Call the modal window, fill and submit.
     *
     * @param string $title
     *   The title of the menu item.
     */
    protected function modalWithAjaxCall($title)
    {
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();
        $modal->createMenuItemForm->internalLinkPath->clear();
        $ajax_service = new AjaxService($this);
        $ajax_service->markAsWaitingForAjaxCallback($modal->createMenuItemForm->internalLinkPath->getWebdriverElement());
        $modal->createMenuItemForm->internalLinkPath->fill($title);

        $autoComplete = new AutoComplete($this);
        $autoComplete->waitUntilSuggestionCountEquals(1);

        // Use the arrow keys to select the result, and press enter to confirm.
        $this->keys(Keys::DOWN . Keys::ENTER);
        $modal->createMenuItemForm->internalLinkRadioButton->select();
        $ajax_service->waitForAjaxCallback($modal->createMenuItemForm->internalLinkPath->getWebdriverElement());
        $modal->submit();
    }
}
