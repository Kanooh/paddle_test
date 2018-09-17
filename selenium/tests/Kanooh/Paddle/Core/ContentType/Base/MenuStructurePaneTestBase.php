<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplayPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\MenuStructurePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;
use Kanooh\Paddle\Pages\Node\DeletePage\DeletePage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the menu structure pane.
 */
abstract class MenuStructurePaneTestBase extends WebDriverTestCase
{
    const CACHE_HIT = 'hit';
    const CACHE_MISS = 'miss';

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The administrative node view.
     *
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node delete confirmation page.
     *
     * @var DeletePage
     */
    protected $deletePage;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var int
     */
    protected $original_cache;

    /**
     * @var array
     */
    protected $original_authcache_roles;

    /**
     * @var boolean
     */
    protected $original_elysia_cron_disable;

    /**
     * The search tab of the content discovery tabs.
     *
     * @var SearchPage
     */
    protected $searchPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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
     * Returns the layout page for the content type that is being tested.
     *
     * @return PaddlePanelsDisplayPage
     *   The layout page of the content type that is being tested.
     */
    abstract public function getLayoutPage();

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
        $this->deletePage = new DeletePage($this);
        $this->editPage = new EditPage($this);
        $this->searchPage = new SearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Temporarily disable page caching because it prevents more granular
        // menu pane cache testing for anonymous users.
        $this->original_cache = variable_get('cache', 0);
        $this->original_authcache_roles = variable_get('authcache_roles', array());
        variable_set('cache', 0);
        variable_set('authcache_roles', array());

        // Remember original Elysia cron status.
        $this->original_elysia_cron_disable = variable_get('elysia_cron_disabled', false);
    }

    /**
     * Tests the default menu when adding a menu structure pane.
     *
     * @group menu
     * @group menuStructurePane
     * @group panes
     */
    public function testMainMenuFirst()
    {
        // Create a basic page and add it to several menus.
        $page_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $page_nid = $this->setupNode($page_title);
        $this->editPage->go($page_nid);
        $this->editPage->addOrEditNodeMenuItem(null, $page_title, 'footer_menu_nl');
        $this->editPage->addOrEditNodeMenuItem(null, $page_title, 'top_menu_nl');
        $this->editPage->addOrEditNodeMenuItem(null, $page_title, 'main_menu_nl');
        $this->editPage->addOrEditNodeMenuItem(null, $page_title, 'disclaimer_menu_nl');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Publish the basic page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Add a menu structure pane on the page and assert that the default
        // menu is the main navigation menu.
        $this->administrativeNodeViewPage->go($page_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();

        $layout_page = $this->getLayoutPage($this);
        $layout_page->checkArrival();

        $region = $layout_page->display->getRandomRegion();
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $menu_pane = new MenuStructurePanelsContentType($this);

        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($menu_pane);
        $default_pane_label = $menu_pane->menu->getSelectedLabel();
        $this->assertEquals('Hoofdnavigatie', $default_pane_label);

        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();
        $layout_page->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests caching of menu structure panes.
     *
     * @group menu
     * @group menuStructurePane
     * @group panes
     */
    public function testCaching()
    {
        // Create a basic page and add it on the first level of the main menu.
        $top_level_page_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $mother_nid = $this->contentCreationService->createBasicPage($top_level_page_title);
        $this->editPage->go($mother_nid);
        $top_level_page_mlid = $this->editPage->addOrEditNodeMenuItem(null, $top_level_page_title, 'main_menu_nl');

        $this->editPage->contextualToolbar->buttonSave->click();

        $this->administrativeNodeViewPage->checkArrival();

        // Publish the basic page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // When adding menu items through the node edit interface, they are
        // inserted in the menu based on the alphabetical order of the menu
        // title. We generate the titles first to ensure the order of nodes
        // in our array equals the alphabetical order of their menu titles.
        // We also generate a 7th title for later usage.
        for ($i = 0; $i < 7; $i++) {
            $titles[] = $this->alphanumericTestDataProvider->getValidValue(8);
        }
        asort($titles);

        // Add 6 pages on the 2nd level of the menu, beneath the basic page we
        // created earlier.
        for ($i = 0; $i < 6; $i++) {
            $title = array_shift($titles);
            $nid = $this->contentCreationService->createBasicPage($title);
            $this->editPage->go($nid);
            $this->editPage->addOrEditNodeMenuItem(null, $title, 'main_menu_nl', $top_level_page_mlid);
            $this->editPage->contextualToolbar->buttonSave->click();

            $this->administrativeNodeViewPage->checkArrival();

            // Publish all nodes except the first one.
            if ($i > 0) {
                $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
                $this->administrativeNodeViewPage->checkArrival();
            }

            $nodes[$i] = array(
                'nid' => $nid,
                'menu_title' => $title,
            );
        }

        // Create a page.
        $display_page_nid = $this->setupNode();

        // Temporarily disable cron because it can clear and rebuild menu
        // cache. And we can't predict when exactly it runs during this test.
        // Don't put this earlier in code because content creation may need
        // cron to enable a required app that holds the content type.
        variable_set('elysia_cron_disabled', true);

        // Add a menu structure pane on the page.
        $this->administrativeNodeViewPage->go($display_page_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();

        $layout_page = $this->getLayoutPage($this);
        $layout_page->checkArrival();

        $region = $layout_page->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $menu_pane = new MenuStructurePanelsContentType($this);

        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($menu_pane);

        // Configure the menu pane to show level 2 menu items we created.
        $menu_pane->menu->selectOptionByValue('main_menu_nl');

        $menu_pane->menuItem->selectOptionByLabel('-' . $top_level_page_title);

        // Select 'Level 2' which will skip 'Level 1'.
        $menu_pane->level->selectOptionByValue(2);

        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        $layout_page->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // We expect all menu items (published and unpublished) to be visible.
        $expected = array(
            $this->addUnpublishedSuffix($nodes[0]),
            $nodes[1],
            $nodes[2],
            $nodes[3],
            $nodes[4],
            $nodes[5],
        );

        // Verify the menu pane on the administrative node view. There should
        // be a cache hit, as the pane was rendered on the page layout edit
        // page.
        $this->verifyMenuPane($expected, self::CACHE_HIT);

        // Publish the landing page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify the menu pane once more on the administrative node view.
        $this->verifyMenuPane($expected, self::CACHE_HIT);

        // Verify the pane on the frontend node view.
        $frontend_view_page = new FrontEndViewPage($this);
        $frontend_view_page->go($display_page_nid);
        $this->verifyMenuPane($expected, self::CACHE_HIT);

        // Verify the pane on the frontend node view as an anonymous user. This
        // user should not see the unpublished node.
        $this->userSessionService->logout();
        $expected = array(
            $nodes[1],
            $nodes[2],
            $nodes[3],
            $nodes[4],
            $nodes[5],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Log in again as chief editor, and publish the only remaining draft.
        $this->userSessionService->login('ChiefEditor');

        $node = $nodes[0];
        $this->administrativeNodeViewPage->go($node['nid']);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // We expect the menu item of the newly published node to appear now,
        // without the '(unpublished)' suffix.
        $expected = array(
            $nodes[0],
            $nodes[1],
            $nodes[2],
            $nodes[3],
            $nodes[4],
            $nodes[5],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Same for the anonymous user.
        $this->userSessionService->logout();
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Log in again and unpublish node 5.
        $this->userSessionService->login('ChiefEditor');

        $node = $nodes[5];
        $this->administrativeNodeViewPage->go($node['nid']);
        $this->administrativeNodeViewPage->contextualToolbar->buttonOffline->click();
        $this->administrativeNodeViewPage->checkArrival();

        // We expect node 5 to have the suffix 'unpublished'.
        $expected = array(
            $nodes[0],
            $nodes[1],
            $nodes[2],
            $nodes[3],
            $nodes[4],
            $this->addUnpublishedSuffix($nodes[5]),
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // The anonymous users shouldn't see the unpublished node.
        $this->userSessionService->logout();
        $expected = array(
            $nodes[0],
            $nodes[1],
            $nodes[2],
            $nodes[3],
            $nodes[4],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Log in again and remove node 3 from the menu via its page properties.
        $this->userSessionService->login('ChiefEditor');

        $node = $nodes[3];
        $this->editPage->go($node['nid']);
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        // We should have only one menu item.
        $item = reset($items);
        $this->editPage->deleteMenuItem($item);

        // We expect node 3 to have disappeared from the menu pane.
        $expected = array(
            $nodes[0],
            $nodes[1],
            $nodes[2],
            $nodes[4],
            $this->addUnpublishedSuffix($nodes[5]),
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Same again for anonymous user.
        $this->userSessionService->logout();
        $expected = array(
            $nodes[0],
            $nodes[1],
            $nodes[2],
            $nodes[4],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Log in again and move the menu item of node 0 to the footer menu,
        // via its page properties.
        $this->userSessionService->login('ChiefEditor');

        $this->editPage->go($nodes[0]);

        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        // We should have only one menu item.
        $item = reset($items);
        $this->editPage->addOrEditNodeMenuItem($item, null, 'footer_menu_nl');

        // We expect node 0 to have disappeared from the menu pane.
        $expected = array(
            $nodes[1],
            $nodes[2],
            $nodes[4],
            $this->addUnpublishedSuffix($nodes[5]),
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Same again for anonymous user.
        $this->userSessionService->logout();
        $expected = array(
            $nodes[1],
            $nodes[2],
            $nodes[4],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Log in again and change the title of the menu item for node 1.
        $this->userSessionService->login('ChiefEditor');

        $node =& $nodes[1];

        $this->editPage->go($node['nid']);
        $node['menu_title'] = array_shift($titles);
        $items = $this->editPage->nodeMenuItemList->getMenuItems();
        // We should have only one menu item.
        $item = reset($items);
        $this->editPage->addOrEditNodeMenuItem($item, $node['menu_title']);

        // We expect the menu item for node 1 now to appear with its new title,
        // as the last item because its new title comes alphabetically last.
        $expected = array(
            $nodes[2],
            $nodes[4],
            $this->addUnpublishedSuffix($nodes[5]),
            $nodes[1],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);

        // Same again for anonymous user.
        $this->userSessionService->logout();
        $expected = array(
            $nodes[2],
            $nodes[4],
            $nodes[1],
        );
        $this->verifyMenuPaneOnSubsequentLoads($frontend_view_page, $expected, $pane_uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);

        // Restore page cache setting.
        variable_set('cache', $this->original_cache);
        variable_set('authcache_roles', $this->original_authcache_roles);

        // Restore Elysia cron disable setting.
        variable_set('elysia_cron_disabled', $this->original_elysia_cron_disable);

        parent::tearDown();
    }

    /**
     * Verify the contents of the menu structure pane.
     *
     * @param array $expectedMenuItems
     *   The expected menu items.
     * @param string $expectedCacheIndicator
     *   Expected cache indicator. Use the CACHE_MISS and CACHE_HIT constants.
     *
     * @todo We do not have classes yet to target specific regions and the
     *   panels inside of them, in the panels renderer. The approach needs to be
     *   discussed first, therefore code to address the menu pane directly
     *   was added in this test class instead.
     */
    public function verifyMenuPane($expectedMenuItems, $expectedCacheIndicator)
    {
        $pane = $this->byCss('#block-system-main div.pane-add-menu-structure');
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $pane */
        $pane_body = $pane->byCssSelector('div.pane-section-body');
        $wrapper_div = $pane_body->byCssSelector('div.ul-menu-items');
        $this->assertEquals($expectedCacheIndicator, $wrapper_div->attribute('data-cache'));

        $list_items = $pane_body->elements($this->using('css selector')->value('li'));
        $this->assertCount(count($expectedMenuItems), $list_items);

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $item */
        foreach ($list_items as $i => $item) {
            $this->assertEquals($expectedMenuItems[$i]['menu_title'], $item->text());
        }
    }

    /**
     * Verifies the contents of the menu pane on subsequent loads.
     *
     * On the first load, asserts there is a cache miss. On the second load,
     * asserts there is a cache hit.
     *
     * @param PaddlePage $page
     *   The previously initialized page to reload.
     * @param $expectedMenuItems
     *   The expected menu items.
     * @param string $pane_uuid
     *   The uuid of the front end pane.
     */
    protected function verifyMenuPaneOnSubsequentLoads(PaddlePage $page, $expectedMenuItems, $pane_uuid)
    {
        // First time a cache miss.
        $page->reloadPage();
        // Get the front-end pane.
        $frontend_pane = new MenuStructurePane($this, $pane_uuid);
        $frontend_pane->waitUntilPaneLoaded();
        $this->verifyMenuPane($expectedMenuItems, self::CACHE_MISS);

        // Second, third and ... time: a cache hit.
        $page->reloadPage();
        // Get the front-end pane.
        $frontend_pane = new MenuStructurePane($this, $pane_uuid);
        $frontend_pane->waitUntilPaneLoaded();
        $this->verifyMenuPane($expectedMenuItems, self::CACHE_HIT);

        $page->reloadPage();
        // Get the front-end pane.
        $frontend_pane = new MenuStructurePane($this, $pane_uuid);
        $frontend_pane->waitUntilPaneLoaded();
        $this->verifyMenuPane($expectedMenuItems, self::CACHE_HIT);
    }

    /**
     * Helper function. Appends the word 'unpublished' to the node-item mapping.
     *
     * @param array $mapping
     *   An associative array as used in $this->testCaching(), with the keys
     *   'nid' and 'menu_title'.
     *
     * @return array
     *   The updated mapping.
     */
    protected function addUnpublishedSuffix(array $mapping)
    {
        $mapping['menu_title'] .= ' (unpublished)';
        return $mapping;
    }
}
