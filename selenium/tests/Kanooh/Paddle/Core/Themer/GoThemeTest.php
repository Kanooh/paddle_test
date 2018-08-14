<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\GoThemeTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class GoThemeTest
 * @package Kanooh\Paddle\Core\Themer
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GoThemeTest extends WebDriverTestCase
{

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
    * @var ViewPage
    */
    protected $viewPage;

    /**
     * Basic page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

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
    * {@inheritdoc}
    */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminViewPage = new AdminViewPage($this);
        $this->viewPage = new ViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Enable the go_theme.
        module_enable(array('paddle_go_themes'));
        drupal_flush_all_caches();

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
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
    * Tests the restriction imposed by the GO theme on the theme edit form.
    *
    * @group themer
    */
    public function testGoThemeEditForm()
    {
        $this->themerOverviewPage->go();

        // Verify the go theme is present on the overview page.
        $this->assertEquals('GO theme', $this->themerOverviewPage->theme('go_theme')->title->text());

        // Verify you can create a new theme based on the go_theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('go_theme');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $go_based_theme = $this->themerEditPage->getThemeName();

        // Check that the wanted form elements are (not) present.
        $this->assertFormElementsCorrect();

        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->assertTextPresent($this->themerOverviewPage->theme($go_based_theme)->title->text());
    }

    /**
    * Checks that the correct form elements are (not) present.
    */
    public function assertFormElementsCorrect()
    {
        // Tests the branding options.
        $this->assertTrue($this->themerEditPage->branding->colorPaletteRadios->isDisplayed());
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-branding-form-elements-branding-global-header-vo-branding'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-branding-form-elements-branding-global-header-global-vo-tokens-header'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-branding-form-elements-branding-global-header-global-vo-tokens-footer'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-branding-favicon'));

        // Tests the header options.
        $this->themerEditPage->header->header->click();
        $this->assertTrue($this->themerEditPage->header->logo->isDisplayed());
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-website-header-styling'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-search-box'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-menu-style'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'edit-header-website-header-sections-form-elements-header-title-font-font-family'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'edit-header-website-header-sections-form-elements-header-subtitle-font-font-family'));

        // Tests the body options.
        $this->themerEditPage->body->header->click();
        $this->assertTrue($this->themerEditPage->body->showBreadcrumbTrailCheckboxForOtherPages->isChecked());
        $this->assertTrue($this->themerEditPage->body->nextLevelCheckboxesUnchecked());
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-body-styling-sections-form-elements-body-background-background-pattern'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'body-text'));

        // Tests the footer options.
        $this->themerEditPage->footer->header->click();
        $this->assertTrue($this->isElementByPropertyPresent('id', 'footer-structure'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-footer-background'));
        $this->assertFalse($this->isElementByPropertyPresent('name', 'footer[styling][sections][form_elements][footer_background][color_enabled]'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-footer-styling-sections-form-elements-footer-background-background-color'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-footer-level-1-menu-items-font'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-footer-level-2-menu-items-font'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-disclaimer-link-font'));
    }
}
