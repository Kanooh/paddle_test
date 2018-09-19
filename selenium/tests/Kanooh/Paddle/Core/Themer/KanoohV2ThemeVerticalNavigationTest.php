<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\KanoohV2ThemeVerticalNavigationTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Apps\FlyOutMenu;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class KanoohV2ThemeVerticalNavigationTest
 * @package Kanooh\Paddle\Core\Themer
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class KanoohV2ThemeVerticalNavigationTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

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

        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the vo branding settings.
     *
     * @group themer
     */
    public function testVoBranding()
    {
        $this->themerOverviewPage->go();

        // Verify you can create a new theme based on the kanooh_theme_v2_vertical_navigation.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_vertical_navigation');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        // Set the VO tokens.
        $this->themerEditPage->header->brandingOptions->yesVoBranding->select();
        $this->themerEditPage->header->voHeaderToken->fill('7308abf9e3634a2e90011e6629c04d36');
        $this->themerEditPage->header->voFooterToken->fill('92bb34889dd34f71b6088af0fba9156c');
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Verify that the VO tokens were saved.
        $this->themerEditPage->go($theme_name);
        $this->assertEquals('7308abf9e3634a2e90011e6629c04d36', $this->themerEditPage->header->voHeaderToken->getContent());
        $this->assertEquals('92bb34889dd34f71b6088af0fba9156c', $this->themerEditPage->header->voFooterToken->getContent());

        // Save the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Tests the background plugin for the kanooh v2 theme.
     */
    public function testBackgroundImageFieldValidation()
    {
        $this->themerOverviewPage->go();

        // Verify you can create a new theme based on the kanooh_theme_v2_vertical_navigation.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_vertical_navigation');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the body section.
        $this->themerEditPage->body->header->click();

        // Select to upload an image.
        $this->themerEditPage->body->backgroundPatternRadios->uploadImage->click();
        $this->themerEditPage->body->backgroundImage->waitUntilDisplayed();

        // Set a background image, save and verify that we are successfully
        // redirected to the next page.
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $this->themerEditPage->body->backgroundImage->chooseFile($file_path);
        $this->themerEditPage->body->backgroundImage->uploadButton->click();
        $this->themerEditPage->body->backgroundImage->waitUntilFileUploaded();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Edit the theme again and remove the image.
        // Save the theme and verify that we are successfully redirected to the
        // next page.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->body->header->click();
        $this->themerEditPage->body->backgroundImage->removeButton->click();
        $this->themerEditPage->body->backgroundImage->waitUntilFileRemoved();
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/budapest.jpg');
        $this->themerEditPage->body->backgroundImage->chooseFile($file_path);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Tests the menu style options for the kanooh v2 theme.
     */
    public function testMenuStyleSettings()
    {
        // Enable the fly out for testing.
        $this->appService->enableApp(new FlyOutMenu);
        $this->themerOverviewPage->go();

        // Verify you can create a new theme based on the kanooh_theme_v2_vertical_navigation.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_vertical_navigation');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();


        // Verify that there is no menu styles options.
        $this->themerEditPage->go($theme_name);
        $this->assertTextNotPresent('Menu style');
        $this->assertTextNotPresent('Mega dropdown');
        $this->assertTextNotPresent('Fly-out menu');
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Tests the footer links settings.
     */
    public function testFooterLinks()
    {
        $this->themerOverviewPage->go();

        // Verify you can create a new theme based on the kanooh_theme_v2_vertical_navigation.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_vertical_navigation');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Verify that the footer links are present.
        $this->themerEditPage->footer->header->click();
        $this->assertNotEmpty($this->themerEditPage->footer->footerLinksLevel1);
        $this->assertNotEmpty($this->themerEditPage->footer->footerLinksLevel2);
        $this->assertNotEmpty($this->themerEditPage->footer->disclaimerLinks);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }
}
