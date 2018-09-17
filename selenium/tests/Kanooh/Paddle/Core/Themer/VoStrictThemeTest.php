<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\VoStrictThemeTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\MenuCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class VoStrictThemeTest
 * @package Kanooh\Paddle\Core\Themer
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VoStrictThemeTest extends WebDriverTestCase
{
    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var MenuCreationService
     */
    protected $menuCreationService;

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

        $this->frontPage = new FrontPage($this);
        $this->menuCreationService = new MenuCreationService($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

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
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the restriction imposed by the vo_strict theme on the theme edit form.
     *
     * @group themer
     */
    public function testVoStrictThemeEditForm()
    {
        // Create a new theme based on the VO Strict theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_strict');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $vo_based_theme = $this->themerEditPage->getThemeName();

        // Check that the wanted form elements are (not) present.
        $this->assertFormElementsCorrect();

        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Create another theme based on the previous one to make sure that even
        // grandchildren respect the restrictions imposed by the VO Strict theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue($vo_based_theme);
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Check that the wanted form elements are (not) present.
        $this->assertFormElementsCorrect();
    }

    /**
     * Tests the vo strict theme on the frond end.
     *
     * @group themer
     */
    public function testVoStrictThemeFrontEnd()
    {
        // Create a random menu item for later use.
        $this->menuCreationService->createRandomMenuItem();

        // Enable the vo strict theme.
        $this->themerOverviewPage->go();

        if ($this->themerOverviewPage->getActiveTheme()->title->text() != 'VO Theme') {
            $this->themerOverviewPage->theme('vo_strict')->enable->click();
        }

        // Go to the front page and verify the correct css settings have been
        // set.
        $this->frontPage->go();

        // Check the background pattern.
        $background_pattern = $this->byCssSelector('.content-background-canvas')->css('background-image');
        $this->assertContains('vo_background.png', $background_pattern);

        // Check the default logo.
        $this->assertContains('vo_theme/logo.png', $this->frontPage->logo->attribute('src'));

        // Check the font.
        $font = $this->byCssSelector('body')->css('font-family');
        $this->assertEquals('"FlandersArtSans-Light","Lucida Sans Unicode","Lucida Grande",sans-serif', $font);

        // Check that the search box is shown by default.
        $this->assertTrue($this->frontPage->searchBox->isPresent());

        // Check that the main menu is shown by default.
        $this->assertTrue($this->frontPage->mainMenuDisplay->isPresent());
    }

    /**
     * Checks that the correct form elements are (not) present.
     */
    public function assertFormElementsCorrect()
    {
        // Tests the branding options.
        $this->assertFalse($this->themerEditPage->branding->colorPaletteRadios->isDisplayed());
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-branding-form-elements-branding-global-header-vo-branding'));
        $this->assertTrue($this->themerEditPage->voHeaderToken->isDisplayed());
        $this->assertTrue($this->themerEditPage->voFooterToken->isDisplayed());
        $this->assertTrue($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-branding-favicon'));

        // Tests the header options.
        $this->themerEditPage->header->header->click();
        $this->assertTrue($this->themerEditPage->header->logo->isDisplayed());
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-website-header-styling'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-search-box'));
        $this->assertTrue($this->isElementByPropertyPresent('id', 'header-menu-style'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-header-website-header-sections-form-elements-header-title-font-font-family'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'edit-header-website-header-sections-form-elements-header-subtitle-font-font-family'));

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
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-footer-level-1-menu-items-font'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-footer-level-2-menu-items-font'));
        $this->assertFalse($this->isElementByPropertyPresent('id', 'paddle-style-plugin-instance-disclaimer-link-font'));
    }
}
