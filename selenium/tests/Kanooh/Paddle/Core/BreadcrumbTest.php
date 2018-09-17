<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\BreadcrumbTest.
 */

namespace Kanooh\Paddle\Core;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BreadcrumbTest extends WebDriverTestCase
{
    /**
     * The add content page.
     *
     * @var AddPage
     */
    protected $addPage;

    /**
     * The admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * Menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /*
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
     * Node front-end view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * The random data generator.
     *
     * @var Random
     */
    protected $random;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->addPage = new AddPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->adminViewPage = new AdminViewPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->viewPage = new ViewPage($this);
        $this->random = new Random();

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Make sure that breadcrumbs are enabled.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme and enable it.
        $human_theme_name = $this->random->name(8);
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();

        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->body->header->click();
        $this->themerEditPage->body->showBreadcrumbTrailCheckboxForBasicPages->check();
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();
        $this->userSessionService->logout();
    }

    /**
     * Tests the breadcrumbs for current active trial.
     * The breadcrumb used to be hidden when the menu title is the same as the page title or page url.
     * See - KANWEBS-5133.
     *
     * @group breadcrumbs
     */
    public function testBreadcrumbsActiveTrial()
    {
        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create a basic page so we can have a menu item.
        $title = $this->alphanumericTestDataProvider->getValidValue(4);
        $nid = $this->contentCreationService->createBasicPageViaUI($title);

        // Add links to the pages to the menu.
        $this->menuOverviewPage->go('main_menu_nl');
        $this->nodeMenuLinkTitle($title);
        $values = array(
            'title' => $title,
            'internal_link' => "node/$nid",
        );
        $this->menuOverviewPage->createMenuItem($values);

        // Go to the last page and verify that the breadcrumb is shown correctly.
        $this->viewPage->go($nid);
        // Make sure there are 2 links in the breadcrumb.
        $links = $this->viewPage->breadcrumb->getLinks();
        $this->assertEquals(2, count($links));
    }

    /**
     * Tests the breadcrumbs.
     *
     * @dataProvider breadcrumbDataProvider
     *
     * @group breadcrumbs
     */
    public function testBreadcrumbs($menu_id, $menu_name)
    {
        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create 3 basic pages so we can have a menu structure with 3 levels.
        $nids = array(
            $this->contentCreationService->createBasicPageViaUI(),
            $this->contentCreationService->createBasicPageViaUI(),
            $this->contentCreationService->createBasicPageViaUI(),
        );
        $last_nid = end($nids);

        // Add links to the pages to the menu.
        $this->menuOverviewPage->go($menu_id);
        $parents = array();
        $parent_mlid = 0;
        foreach ($nids as $nid) {
            $item_title = $this->nodeMenuLinkTitle($nid);
            $values = array(
                'title' => $item_title,
                'parent' => "$menu_name:$parent_mlid",
                'internal_link' => "node/$nid",
            );
            $parent_mlid = $this->menuOverviewPage->createMenuItem($values, $parents);
            $parents[] = $parent_mlid;
        }
        // Go to the last page and verify that the breadcrumb is shown
        // correctly.
        $this->viewPage->go($last_nid);
        $this->assertCompleteBreadcrumb($nids, $menu_name);
    }

    /**
     * Dataprovider for the breadcrumb test.
     */
    public function breadcrumbDataProvider()
    {
        return array(
            array(
                MenuOverviewPage::MAIN_MENU_ID,
                MenuOverviewPage::MAIN_MENU_NAME
            ),
            array(
                MenuOverviewPage::FOOTER_MENU_ID,
                MenuOverviewPage::FOOTER_MENU_NAME
            ),
            array(
                MenuOverviewPage::TOP_MENU_ID,
                MenuOverviewPage::TOP_MENU_NAME
            ),
            array(
                MenuOverviewPage::DISCLAIMER_MENU_ID,
                MenuOverviewPage::DISCLAIMER_MENU_NAME
            ),
        );
    }

    /**
     * Generates a static menu link title for a node.
     *
     * @param int $nid
     *   Node id.
     *
     * @return string
     *   String containing the word 'Node ' and the passed $nid.
     */
    public function nodeMenuLinkTitle($nid)
    {
        return 'Node ' . $nid;
    }

    /**
     * Verifies the breadcrumb on the current page.
     *
     * @param int[] $nids
     *   List of node ids of the pages supposed to be in the breadcrumb. Should
     *   include the current page.
     *
     * @param string $menu_name
     *   The name of the menu which is assigned to the breadcrumb
     */
    public function assertCompleteBreadcrumb($nids, $menu_name)
    {
        $breadcrumb = $this->viewPage->breadcrumb;
        $links = $breadcrumb->getLinks();

        // Make sure we have at least one item, and remove it. (It should be
        // the homepage.)
        $this->assertFalse(empty($links));

        // Loop over each link and make sure it's the correct page.
        for ($i = 0; $i < count($nids) - 1; $i++) {
            $expected = $this->nodeMenuLinkTitle($nids[++$i]);
            $this->assertEquals($expected, $links[++$i]->getText());
        }

        // Lastly check that the last element is the current page's title if the
        // menu is not the Main menu and vice versa.
        $node = node_load(end($nids));
        if ($menu_name != MenuOverviewPage::MAIN_MENU_NAME) {
            $this->assertEquals($node->title, end($links)->getText());
        } else {
            $this->assertNotEquals($node->title, end($links)->getText());
        }
    }
}
