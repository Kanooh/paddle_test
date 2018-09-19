<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\OverlappingPanesTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MenuCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that panes don't overlap each other.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-3776
 */
class OverlappingPanesTest extends WebDriverTestCase
{
    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var MenuCreationService
     */
    protected $menuCreationService;

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

        // Instantiate the Pages that will be visited in the test.
        $this->layoutPage = new PanelsContentPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->menuCreationService = new MenuCreationService($this);

        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
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
     * Tests that panes don't overlap each other.
     *
     * @group menu
     * @group menuStructurePane
     * @group panes
     * @group regression
     */
    public function testOverlappingPanes()
    {
        // Create a basic page and add a menu item for it.
        $bp_nid = $this->contentCreationService->createBasicPage();
        $mlid = $this->menuCreationService->createNodeMenuItem($bp_nid);

        // Add a landing page with a small left column.
        $lp_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($lp_nid);

        // Open the "Add page content" pane configuration form.
        $region = $this->layoutPage->display->region(Paddle2Col3to9Layout::REGIONA);
        $pane = $region->addPane(new CustomContentPanelsContentType($this));

        // Create a menu structure pane.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $menu_pane = new MenuStructurePanelsContentType($this);
        $modal->selectContentType($menu_pane);
        $menu_pane->menu->selectOptionByValue('main_menu_nl');

        // Set the selected menu item to unpublished basic page
        // so that is shows "Unpublished" banner.
        $menu_pane->menuItem->selectOptionByValue($mlid);

        $modal->submit();
        $modal->waitUntilClosed();

        $this->layoutPage->checkArrival();

        // Verify you can still click the buttons of the other pane.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();
        $this->layoutPage->checkArrival();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
