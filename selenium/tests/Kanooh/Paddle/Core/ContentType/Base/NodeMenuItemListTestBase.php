<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\DeleteMenuItemModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\EditPage as MenuItemEditPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\NodeMenuItemList\NodeMenuItem;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\EditPage\MenuItemModal;
use Kanooh\Paddle\Pages\Node\EditPage\MenuItemPositionModal;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Base test for the menu item list on node edit pages.
 */
abstract class NodeMenuItemListTestBase extends WebDriverTestCase
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var MenuItemEditPage
     */
    protected $menuItemEditPage;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var Random
     */
    protected $random;
    /**
     * @var AddPage
     */
    protected $addPage;


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

        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->editPage = new EditPage($this);
        $this->menuItemEditPage = new MenuItemEditPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->random = new Random();

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        // Clean up the Paddle menu items.
        $this->cleanUpService->deleteMenuItems();

        parent::tearDown();
    }

    /**
     * Tests the managing of menu items on the node edit page.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group modals
     * @group nodeMenuItemListTestBase
     */
    public function testMenuItemList()
    {
        // Create a landing page and publish it. It needs to be published so we
        // can add children to it in the navigation later on.
        $this->addContentPage->go();
        $lp_nid = $this->contentCreationService->createLandingPage();
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();

        // Create a node and publish it.
        $this->addContentPage->go();
        $bp_nid = $this->contentCreationService->createBasicPage();
        $this->adminNodeViewPage->go($bp_nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go to the landing page's page properties and add a menu item.
        $this->editPage->go($lp_nid);
        $lp_menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $lp_mlid = $this->editPage->addOrEditNodeMenuItem(null, $lp_menu_title);

        // Make sure the menu item is displayed correctly.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        $this->assertEquals('Hoofdnavigatie', $items[$lp_mlid]->menuTitle);
        $this->assertEquals($lp_menu_title, $items[$lp_mlid]->breadcrumb[0]);

        // We have no way of generating the correct path in Selenium.
        $this->assertNotEmpty($items[$lp_mlid]->viewIcon->attribute('href'));

        // Save the current page to get past the alert box.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Now go to the node, add 2 menu items, and check again that they
        // are both displayed correctly.
        $this->editPage->go($bp_nid);

        $bp_menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $bp_menu_title, 'main_menu_nl', $lp_mlid);
        $bp_footer_menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $bp_footer_menu_title, 'footer_menu_nl');

        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(2, $items);

        // Check the first item.
        reset($items);
        $mlid = key($items);
        $this->assertEquals('Footernavigatie', $items[$mlid]->menuTitle);
        $this->assertEquals($bp_footer_menu_title, $items[$mlid]->breadcrumb[0]);
        $this->assertNotEmpty($items[$mlid]->viewIcon->attribute('href'));

        // Check the second item.
        next($items);
        $mlid = key($items);
        $this->assertEquals('Hoofdnavigatie', $items[$mlid]->menuTitle);
        $this->assertEquals($lp_menu_title, $items[$mlid]->breadcrumb[0]);
        $this->assertEquals($bp_menu_title, $items[$mlid]->breadcrumb[1]);
        $this->assertNotEmpty($items[$mlid]->viewIcon->attribute('href'));

        // Check if an existing item can be edited.
        $items[$mlid]->editIcon->click();
        $modal = new MenuItemModal($this);
        $modal->waitUntilOpened();

        // Check that the original name and parent menu link are preselected.
        $this->assertEquals($bp_menu_title, $modal->title->getContent());
        $this->assertEquals('main_menu_nl', $modal->navigation->value());

        // Change the title and move the item to the Top Navigation menu.
        $title = $this->random->name(10);
        $modal->title->fill($title);
        $modal->navigation->selectOptionByValue('top_menu_nl');
        $modal->submit();
        $modal = new MenuItemPositionModal($this);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();

        // Check that the menu item is still present and has the new title and
        // menu position.
        $this->editPage->checkArrival();
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(2, $items);

        end($items);
        $mlid = key($items);
        $this->assertEquals('Topnavigatie', $items[$mlid]->menuTitle);
        $this->assertEquals($title, $items[$mlid]->breadcrumb[0]);

        // Delete the menu items.
        foreach ($items as $item) {
            // The menu items list is reloaded each time a delete has been
            // executed so we need to redefine the menu item.
            $new_item = new NodeMenuItem($this, $item->mlid);
            $this->deleteMenuItem($new_item);
        }

        // Go back to the landing page and delete its menu item too.
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->adminNodeViewPage->checkArrival();
        $this->editPage->go($lp_nid);
        $items = $this->editPage->nodeMenuItemList->getMenuItems();

        // The item should still be there at this point, so verify it is before
        // deleting it.
        $this->assertCount(1, $items);
        $this->deleteMenuItem(reset($items));
    }

    /**
     * Tests that an editor cannot edit or delete links to published nodes.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group modals
     * @group nodeMenuItemListTestBase
     */
    public function testMenuItemAccess()
    {
        // Create the node and publish it.
        $nid = $this->setupNode();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Add a menu item to the node.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $menu_title);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Log in as editor.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');

        // Edit the node and check that the menu item is present and the delete
        // icon too.
        $this->editPage->go($nid);
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        /* @var $item NodeMenuItem */
        $item = reset($items);
        $this->assertNotEmpty($item->deleteIcon);

        // Save the node edit form so subsequent tests are not confronted with
        // an alert box.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Test the behaviour of the close buttons in the node menu item modals.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group modals
     * @group nodeMenuItemListTestBase
     */
    public function testModalClose()
    {
        // Instantiate the modals we will use in this test.
        $first_modal = new MenuItemModal($this);
        $second_modal = new MenuItemPositionModal($this);

        // Create a node.
        $nid = $this->setupNode();

        // Go to the node edit form.
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Create a menu item to test with.
        $menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $menu_title);

        // Edit the item, navigate to the second modal, and close it by pressing
        // the escape key. Check that the menu item is successfully updated.
        // Run this test twice to verify that the key handlers are successfully
        // rebound after the ajax call completes.
        for ($i = 0; $i < 2; $i++) {
            // Reinitialize the page elements so we can pick up the menu link from
            // the node menu item list which has been refreshed with ajax.
            $this->editPage->checkArrival();
            $items = $this->editPage->nodeMenuItemList->getMenuItems();
            $item = reset($items);

            $item->editIcon->click();
            $first_modal->waitUntilOpened();

            $title = $this->random->name(10);
            $first_modal->title->fill($title);
            $first_modal->submit();
            $this->waitUntilTextIsPresent('The menu item has been updated. Please drag it to the desired location.');

            $this->keys(Keys::ESCAPE);
            $this->waitForText($title);
        }

        // Repeat the test, but this time clicking the close button rather than
        // pressing the escape key. Run it twice to check if the click handler is
        // successfully rebound to subsequent modals.
        for ($i = 0; $i < 2; $i++) {
            // Reinitialize the page elements so we can pick up the menu link from
            // the node menu item list which has been refreshed with ajax.
            $this->editPage->checkArrival();
            $items = $this->editPage->nodeMenuItemList->getMenuItems();
            $item = reset($items);

            $item->editIcon->click();
            $first_modal->waitUntilOpened();

            $title = $this->random->name(10);
            $first_modal->title->fill($title);
            $first_modal->submit();
            $this->waitUntilTextIsPresent('The menu item has been updated. Please drag it to the desired location.');

            $second_modal->close();
            $this->waitForText($title);
        }
    }

    /**
     * Tests the updating/deleting of the first menu item in the node edit page.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group nodeMenuItemListTestBase
     */
    public function testFirstMenuItem()
    {
        // Create a node.
        $bp_nid = $this->setupNode();
        $this->adminNodeViewPage->go($bp_nid);

        // Go to the node edit page and add a menu item.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->moveto($this->editPage->seoDescriptionField);
        $link_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $link_title);

        // Make sure the menu item is displayed correctly.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        // Save the current page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Go to the edit page and edit the menu item.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        // Get the first item and edit it.
        reset($items);
        $mlid = key($items);
        $items[$mlid]->editIcon->click();
        $modal = new MenuItemModal($this);
        $modal->waitUntilOpened();

        // Check that the original name and parent menu link are preselected.
        $this->assertEquals($link_title, $modal->title->getContent());
        $this->assertEquals('main_menu_nl', $modal->navigation->value());

        // Change the title.
        $new_link_title = $this->random->name(6);
        $modal->title->fill($new_link_title);
        $modal->submit();
        $modal = new MenuItemPositionModal($this);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();

        // Check that the menu item is still present and has the new title.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        // Save the current page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Go to the edit page verify the title is set correctly.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        // Get the first item and edit it.
        reset($items);
        $mlid = key($items);
        $items[$mlid]->editIcon->click();
        $modal = new MenuItemModal($this);
        $modal->waitUntilOpened();
        // Change the title.
        $this->assertEquals($new_link_title, $modal->title->getContent());
        $modal->close();
        $modal->waitUntilClosed();

        // Delete the menu item.
        foreach ($items as $item) {
            // The menu items list is reloaded each time a delete has been
            // executed so we need to redefine the menu item.
            $new_item = new NodeMenuItem($this, $item->mlid);
            $this->deleteMenuItem($new_item);
        }
        // Save the current page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Go to the edit page verify menu item has been deleted.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(0, $items);

        // Save again, to prevent alert boxes from popping up after this.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }


    /**
     * Tests that node save doesn't affect menu items. It's regression test for
     * https://one-agency.atlassian.net/browse/KANWEBS-2516.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group nodeMenuItemListTestBase
     * @group regression
     */
    public function testNodeSaveMenuItemChange()
    {
        $parent_title = $this->random->name(8);
        $child_title = $this->random->name(8);

        // Create a parent menu item pointing to the front page.
        $this->menuOverviewPage->go();
        $parent_mlid = $this->menuOverviewPage->createMenuItem(array('title' => $parent_title), array());

        // Create page to test with.
        $nid = $this->setupNode();

        // Go to the node edit page and add new menu item.
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $child_mlid = $this->editPage->addOrEditNodeMenuItem(null, $child_title);

        // Make sure there is only one menu item and it's on level 1.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        $this->assertCount(1, $items[$child_mlid]->breadcrumb);
        $this->assertEquals($child_title, $items[$child_mlid]->breadcrumb[0]);

        // Save the node and edit again.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Check that the menu item did not change after the save.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        $this->assertCount(1, $items[$child_mlid]->breadcrumb);
        $this->assertEquals($child_title, $items[$child_mlid]->breadcrumb[0]);

        // Change the menu item title and make it child of the other menu item.
        $new_child_title = $this->random->name(8);
        $this->editPage->addOrEditNodeMenuItem($items[$child_mlid], $new_child_title, 'main_menu_nl', $parent_mlid, 'child');

        // Make sure the menu item is on level 2 and the title is changed.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        $this->assertCount(2, $items[$child_mlid]->breadcrumb);
        $this->assertEquals($parent_title, $items[$child_mlid]->breadcrumb[0]);
        $this->assertEquals($new_child_title, $items[$child_mlid]->breadcrumb[1]);

        // Save the node again and edit again.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        // Do the same checks again to be sure nothing changed.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);
        $this->assertCount(2, $items[$child_mlid]->breadcrumb);
        $this->assertEquals($parent_title, $items[$child_mlid]->breadcrumb[0]);
        $this->assertEquals($new_child_title, $items[$child_mlid]->breadcrumb[1]);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests if the dominant breadcrumb is set by adding the page to the navigation.
     *
     * @group contentType
     * @group editing
     * @group menu
     * @group nodeMenuItemListTestBase
     */
    public function testDominantBreadCrumb()
    {
        // Create a node and add a menu item.
        $bp_nid = $this->setupNode();
        $this->editPage->go($bp_nid);
        $menu_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->addOrEditNodeMenuItem(null, $menu_title);

        // Make sure the menu item is displayed correctly.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        // Assert that the added item is shown as the dominant breadcrumb.
        $this->editPage->go($bp_nid);
        $this->assertTextPresent($menu_title . ' (dominant breadcrumb)');

        // Assert that the FAQ link is also visible.
        $xpath = ('//a[contains(@class, "breadcrumb-faq")]');
        $this->assertContains('http://support.kanooh.be/support/solutions/articles/3000065792-welke-broodkruimel-is-dominant-', $this->byXPath($xpath)->attribute('href'));

        // Create a second menu item through the menu overview page.
        $this->menuOverviewPage->go();
        $second_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->menuOverviewPage->createMenuItem(array('title' => $second_title, 'internal_link' => 'node/' . $bp_nid), array());
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();

        // Assert that the second title is now shown as the dominant breadcrumb.
        $this->editPage->go($bp_nid);
        $this->assertTextPresent($second_title . ' (dominant breadcrumb)');
    }

    /**
     * Tests if you add a blank menu item, that is has the node title filled in.
     */
    public function testNodeTitleIsDefaultLinkTitle()
    {
        if ($this->getContentTypeName() != 'contact_person') {
            $node_title = $this->alphanumericTestDataProvider->getValidValue();
            $nid = $this->setupNode($node_title);
        } else {
            $first_name = $this->alphanumericTestDataProvider->getValidValue();
            $last_name = $this->alphanumericTestDataProvider->getValidValue();
            $nid = $this->setupNode($first_name, $last_name);
            $node_title = $first_name . ' ' . $last_name;
        }


        $this->editPage->go($nid);
        $this->editPage->addOrEditNodeMenuItem();

        // Make sure the menu item is displayed correctly.
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertCount(1, $items);

        // The link title is always part of the breadcrumb, the node title is
        // (unless you fill it in the link title as we did here) not.
        $item = reset($items);
        $this->assertContains($node_title, $item->breadcrumb);
    }

    /**
     * Deletes the given menu item using the modal dialog.
     *
     * @param NodeMenuItem $item
     *   The menu item to delete.
     */
    public function deleteMenuItem(NodeMenuItem $item)
    {
        // Delete the menu item.
        $mlid = $item->mlid;
        $item->deleteIcon->click();
        $modal = new DeleteMenuItemModal($this);
        $modal->waitUntilOpened();
        $modal->submit();
        $modal->waitUntilClosed();

        // Check that the menu item is no longer displayed on the page.
        $this->editPage->checkArrival();
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        $this->assertFalse(array_key_exists($mlid, $items));

        // Check that the menu item is actually deleted in a new window.
        $main_window = $this->windowHandle();

        // @see https://github.com/sebastianbergmann/phpunit-selenium/issues/160
        $this->execute(
            array(
                'script' => 'window.open("' . $this->base_url . '");',
                'args' => array(),
            )
        );

        $handles = $this->windowHandles();
        $new_window = array_pop($handles);
        $this->window($new_window);

        // Couldn't find a way to check for the 404 HTTP status, so checking if
        // the text 'Page not found' is present in the page to assert that the
        // menu item is deleted.
        $this->menuItemEditPage->go(array($item->menu, $mlid));
        $this->assertTextPresent('Page not found');

        // Close the window and return to the main window.
        $this->closeWindow();
        $this->window($main_window);
    }

    /**
     * Returns the position of a menu item in the menu overview form.
     *
     * This is the numeric position, counting starting with 0 from the top.
     *
     * @param int $mlid
     *   The mlid of the menu item to find.
     *
     * @return int
     *   The position of the item in the menu.
     */
    public function getMenuItemPositionByMlid($mlid)
    {
        // @todo Support items that reside on deeper levels.
        /* @var $row \PHPUnit_Extensions_Selenium2TestCase_Element */
        $position = 0;
        foreach ($this->elements($this->using('xpath')->value('//table[@id = "menu-overview"]/tbody/tr')) as $row) {
            if (in_array('mlid-' . $mlid, explode(' ', $row->attribute('class')))) {
                return $position;
            }
            $position++;
        }
    }

    /**
     * Returns the position of a menu item in the menu overview form.
     *
     * This is the numeric position, counting starting with 0 from the top.
     *
     * @param string $title
     *   The title of the menu item to find.
     *
     * @return int
     *   The position of the item in the menu.
     */
    public function getMenuItemPositionByTitle($title)
    {
        // @todo Support items that reside on deeper levels.
        $position = 0;
        foreach ($this->elements($this->using('xpath')->value('//table[@id = "menu-overview"]/tbody/tr')) as $row) {
            if ($row->elements($this->using('xpath')->value('//a[@title = "' . $title . '"]'))) {
                return $position;
            }
            $position++;
        }
    }

    /**
     * Returns a JS script that will focus the given element by the given xpath.
     *
     * @see https://gist.github.com/yckart/6351935
     *
     * @param string $xpath
     *   The XPath query that represents the element that is in need of focus.
     *
     * @deprecated
     *   The place for this method is in the Element class.
     *
     * @return string
     *   The Javascript code.
     */
    protected function focusElementByXPath($xpath)
    {
        $script = "
            var getElementByXPath = function(xpath) {
                return document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
            }
            getElementByXPath(arguments[0]).focus();
        ";
        $this->execute(
            array(
                'script' => $script,
                'args' => array($xpath),
            )
        );
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Get the machine name of the content type.
     *
     * @return string
     *   The machine name of the content type.
     */
    abstract protected function getContentTypeName();
}
