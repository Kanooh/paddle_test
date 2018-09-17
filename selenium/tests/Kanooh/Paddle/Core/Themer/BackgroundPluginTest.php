<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\BackgroundPluginTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the background plugin in the themer.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BackgroundPluginTest extends WebDriverTestCase
{

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var FrontPage
     */
    protected $frontPage;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontPage = new FrontPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests if the background upload image field validation is working.
     *
     * This is a regression test for KANWEBS-3772.
     *
     * @group regression
     * @group themer
     */
    public function testBackgroundImageFieldValidation()
    {
        // Go to the themer overview page.
        $this->themerOverviewPage->go();

        // Create a new theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Pick up a random name and the base theme.
        $human_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Retrieve the machine name of the theme.
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the header section.
        $this->themerEditPage->header->header->click();

        // Select to upload an image.
        $this->themerEditPage->header->backgroundPatternRadios->uploadImage->click();
        $this->themerEditPage->header->backgroundImage->waitUntilDisplayed();

        // Set a background image, save and verify that we are successfully
        // redirected to the next page.
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $this->themerEditPage->header->backgroundImage->chooseFile($file_path);
        $this->themerEditPage->header->backgroundImage->uploadButton->click();
        $this->themerEditPage->header->backgroundImage->waitUntilFileUploaded();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Edit the theme again and remove the image.
        // Save the theme and verify that we are successfully redirected to the
        // next page.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->backgroundImage->removeButton->click();
        $this->themerEditPage->header->backgroundImage->waitUntilFileRemoved();
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/budapest.jpg');
        $this->themerEditPage->header->backgroundImage->chooseFile($file_path);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Tests the header image.
     *
     * @group themer
     */
    public function testHeaderImage()
    {
        // Go to the themer overview page.
        $this->themerOverviewPage->go();

        // Create a new theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Pick up a random name and the base theme.
        $human_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Retrieve the machine name of the theme.
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the header section and upload a header image.
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->headerPatternRadios->uploadImage->click();
        $this->themerEditPage->header->headerImage->waitUntilDisplayed();

        // Set a header image, save and verify that we are successfully
        // redirected to the next page.
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/london.jpg');
        $this->themerEditPage->header->headerImage->chooseFile($file_path);
        $this->themerEditPage->header->headerImage->uploadButton->click();
        $this->themerEditPage->header->headerImage->waitUntilFileUploaded();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        $this->frontPage->go();
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver) {
                try {
                    $element = $webdriver->byCssSelector('header .header-background-canvas')->css('background-image');
                    if (strpos($element, 'london') !== false) {
                        return true;
                    }
                } catch (\Exception $e) {
                      return false;
                }
            }
        );
        $this->waitUntil($callable, $this->timeout);
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
}
