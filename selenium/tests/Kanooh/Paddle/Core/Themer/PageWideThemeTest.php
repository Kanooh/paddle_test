<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\PageWideThemeTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LayoutPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Modal\StylePaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PageWideThemeTest
 * @package Kanooh\Paddle\Core\Themer
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PageWideThemeTest extends WebDriverTestCase
{

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var ViewPage
     */
    protected $viewPage;

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
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminViewPage = new AdminViewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Go back on a theme based on the standard v2 theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        parent::tearDown();
    }

    /**
     * Tests the restriction imposed by the page wide theme on the theme edit form.
     *
     * @group themer
     */
    public function testPageWideThemeEditForm()
    {
        $this->themerOverviewPage->go();

        // Verify the page wide theme is present on the theme overview page.
        $this->assertEquals('kañooh Theme 2.0 - Page wide', $this->themerOverviewPage->theme('kanooh_theme_v2_page_wide')->title->text());

        // Verify you can create a new theme based on the page wide theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_page_wide');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $page_wide_based_theme = $this->themerEditPage->getThemeName();

        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->assertTextPresent($this->themerOverviewPage->theme($page_wide_based_theme)->title->text());
    }

    /**
     * Tests the page wide theme on the frond end.
     *
     * @group themer
     */
    public function testPageWideThemeFrontEnd()
    {
        // Enable the page wide theme.
        $this->enablePageWideTheme();

        $nid = $this->contentCreationService->createLandingPage();
        $this->viewPage->go($nid);

        // Verify that the body contains the page-wide class.
        $this->byCssSelector('body.page-wide');

        // Assert that the max-width class has been removed from the page content.
        $this->assertFalse($this->isElementByPropertyPresent('class', 'max-width'));

        // Assert that the default page padding has been removed.
        $padding = $this->byCssSelector('.paddingizer')->css('padding');
        $this->assertEquals('', $padding);
    }

    /**
     * Tests adding background images on regions within a landing page.
     *
     * @group themer
     */
    public function testPageWideThemeBackgroundImageOnPanels()
    {
        // Enable the page wide theme.
        $this->enablePageWideTheme();

        // Create the test assets.
        $atom = $this->assetCreationService->createImage();
        $nid = $this->contentCreationService->createLandingPage();
        $this->adminViewPage->checkArrival();

        $this->adminViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Change to a layout where you can add an image to.
        $this->layoutPage->changeLayout('paddle_no_column');
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->layoutPage->go($nid);

        // Select a region to put a pane in.
        $region = $this->layoutPage->display->getRandomRegion();
        $region->buttonAddPane->click();

        // Add a Custom Content pane to the region.
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($custom_content_pane);
        $custom_content_pane->fillInConfigurationForm();
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the pane and return to the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->layoutPage->go($nid);

        // Select the page-wide region style and upload a background
        // image.

        $region->styleButton->click();
        $style_modal = new StylePaneModal($this);
        $style_modal->waitUntilOpened();

        $style_modal->regionStyle->pageWideStyle->select();
        $style_modal->submit();
        $style_modal->backgroundImage->selectAtom($atom['id']);
        $style_modal->saveButton->click();
        $style_modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go to the front-end of the landing page.
        $this->viewPage->go($nid);

        // Get the CSS of the background image.
        $background_image_css = $this->byCssSelector('.page-wide.region.is-transparent')->css('background-image');

        // Get the URL of the previously uploaded atom.
        $atom['entity'] = scald_atom_load($atom['id']);
        $expected_url = file_create_url($atom['entity']->file_source);

        // Assert that both the URL can be found in the CSS.
        $this->assertContains($expected_url, $background_image_css);
    }

    /**
     * Tests the customizable header.
     */
    public function testPageWideHeaderPosition()
    {
        // Add a menu item to the main menu.
        $this->menuOverviewPage->go();
        $menu_item_title = 'Ja, Maarten';
        $this->menuOverviewPage->createMenuItem(array('title' => $menu_item_title), array());
        $this->menuOverviewPage->contextualToolbar->buttonSave->click();
        $this->menuOverviewPage->checkArrival();

        $this->frontPage->go();
        // Assert that the menu is shown in the content wrapper.
        try {
            $this->byCssSelector(".content-wrapper #main-nav");
            // Everything is fine.
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail('The navigation should be shown in the content wrapper.');
        }

        // Enable a page wide theme and browse to edit page.
        $theme_name = $this->enablePageWideTheme();
        $this->themerEditPage->go($theme_name);

        // Upload a logo.
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $this->themerEditPage->header->logo->chooseFile($file_path);
        $this->themerEditPage->header->logo->uploadButton->click();
        $this->themerEditPage->header->logo->waitUntilFileUploaded();
        $this->themerEditPage->header->showLogoInHeader->check();

        // Verify that the header position radiobutton is set by default to standard.
        $this->assertEquals('standard', $this->themerEditPage->header->headerPosition->getSelected()->getValue());

        // Select a customized header.
        $this->themerEditPage->header->headerPosition->customized->select();

        // Logo shall be displayed to the left side, and the navigation to the center.
        $this->themerEditPage->header->logoPosition->left->select();
        $this->themerEditPage->header->navigationPosition->center->select();
        $this->themerEditPage->buttonSubmit->click();

        // Go to the front page and assert that the logo and navigation are still shown.
        $this->frontPage->go();
        $this->assertTrue($this->frontPage->logo->displayed());
        $this->assertTrue($this->frontPage->mainMenuDisplay->isPresent());

        // Assert that the menu is not shown in the content wrapper anymore.
        try {
            $this->byCssSelector('.content-wrapper #main-nav');
            $this->fail('The navigation should not be shown in the content wrapper anymore.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }

    /**
     * Tests the customizable header - sticky header.
     */
    public function testPageWideStickyHeader()
    {
        // Enable a page wide theme and browse to edit page.
        $theme_name = $this->enablePageWideTheme();
        $this->themerEditPage->go($theme_name);

        // Select a customized header.
        $this->themerEditPage->header->headerPosition->customized->select();

        // Logo shall be displayed to the left side, and the navigation to the center.
        $this->themerEditPage->header->logoPosition->center->select();
        $this->themerEditPage->header->navigationPosition->center->select();
        $this->themerEditPage->header->stickyHeader->check();
        $this->themerEditPage->buttonSubmit->click();

        // Make sure we can not save.
        $this->waitUntilTextIsPresent('You can not use sticky header with logo and Navigation positioned at the center of the header.');

        // Change the logo position to the left.
        $this->themerEditPage->header->logoPosition->left->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->frontPage->go();

        // Make sure the sticky-header class is added on the front-end.
        $this->byCssSelector('body.sticky-header');
    }

    /**
     * Enables the page wide theme.
     */
    protected function enablePageWideTheme()
    {
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_page_wide');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $page_wide_based_theme = $this->themerEditPage->getThemeName();
        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        $this->themerOverviewPage->theme($page_wide_based_theme)->enable->click();
        $this->themerOverviewPage->checkArrival();

        return $page_wide_based_theme;
    }
}
