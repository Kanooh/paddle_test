<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\FooterTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the footer in the themer.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FooterTest extends WebDriverTestCase
{

    /**
     * The front-end page.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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
     * The random data generator.
     *
     * @var Random
     */
    protected $random;

    /**
     * The user session service.
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

        // Create some instances to use later on.
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->random = new Random();
    }

    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the footer appearance depending of the Themer settings.
     *
     * This is a regression test for KANWEBS-2563.
     * @see https://one-agency.atlassian.net/browse/KANWEBS-2563
     *
     * @group regression
     * @group themer
     */
    public function testFooterAppearance()
    {
        $this->userSessionService->login('ChiefEditor');

        // Create some Menu Links in the Footer menu.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->leftMenuDisplay->getMenuItemLinkByTitle('Footernavigatie')->click();

        // Create the parent menu item.
        $parent_values = array(
            'title' => $this->random->name(8),
            'description' => $this->random->string(20),
        );
        $parent_mlid = $this->menuOverviewPage->createMenuItem($parent_values);
        // Create the child menu item.
        $child_values = array(
            'title' => $this->random->name(8),
            'description' => $this->random->string(20),
            'parent' => "footer_menu_nl:$parent_mlid",
        );
        $child_mlid = $this->menuOverviewPage->createMenuItem($child_values, array($parent_mlid));

        // Make sure the default theme is currently enabled to test with theme
        // which we cannot edit.
        $this->userSessionService->switchUser('SiteManager');
        $this->themerOverviewPage->go();
        if ($this->themerOverviewPage->getActiveTheme()->title->text() != 'kañooh Theme') {
            $vo_theme = $this->themerOverviewPage->getStandardTheme();
            $vo_theme->enable->click();
            $this->themerOverviewPage->checkArrival();
        }

        // Test the default footer style without creating a theme.
        $this->frontPage->go();
        $this->assertNotNull($this->frontPage->footerMenuDisplay->getMenuItemLinkByTitle($parent_values['title']));
        $this->assertNotNull($this->frontPage->footerMenuDisplay->getMenuItemLinkByTitle($child_values['title']));

        // Create new theme.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme and just save it.
        $human_theme_name = $this->random->name(8);
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Array indicating which menu levels should be present for each footer
        // style.
        $footer_style = array(
            'noFooter' => array('parent' => false, 'child' => false),
            'thinFooter' => array('parent' => true, 'child' => false),
            'fatFooter' => array('parent' => true, 'child' => true),
        );

        // Check that the "Footer style" is respected by checking the presence
        // of the Footer menu.
        foreach ($footer_style as $value => $levels) {
            $this->themerOverviewPage->getActiveTheme()->edit->click();

            // Set the "Footer style" value.
            $this->themerEditPage->footer->header->click();
            $this->themerEditPage->footer->$value->select();
            $this->themerEditPage->buttonSubmit->click();
            $this->themerOverviewPage->checkArrival();

            // Go to the front page.
            $this->frontPage->go();

            foreach ($levels as $lvl => $show) {
                $title = $lvl == 'parent' ? $parent_values['title'] : $child_values['title'];
                if ($show) {
                    $this->assertNotNull($this->frontPage->footerMenuDisplay->getMenuItemLinkByTitle($title));
                } else {
                    $this->assertNull($this->frontPage->footerMenuDisplay->getMenuItemLinkByTitle($title));
                }
            }
            $this->themerOverviewPage->go();
        }
    }
}
