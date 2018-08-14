<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\FileUsageTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the file usage in the themer.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FileUsageTest extends WebDriverTestCase
{

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
     * Tests if uploaded files work correctly after a theme is cloned.
     *
     * If a theme that contains a logo is cloned and the logo is subsequently
     * removed in the cloned theme, this should not affect the original theme.
     *
     * This is a regression test for KANWEBS-1980.
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1980
     *
     * @group regression
     * @group themer
     */
    public function testCloneThemeWithFilesUploaded()
    {
        $this->userSessionService->login('SiteManager');

        // Go to the themer overview page and create a new theme.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme.
        $human_theme_name = $this->random->name(8);
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the body section and upload a logo.
        $this->themerEditPage->header->header->click();
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $this->themerEditPage->header->logo->chooseFile($file_path);
        $this->themerEditPage->header->logo->uploadButton->click();
        $this->themerEditPage->header->logo->waitUntilFileUploaded();
        $this->themerEditPage->buttonSubmit->click();

        // Clone the theme and verify there is a file uploaded to the logo
        // field.
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue($theme_name);
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->header->header->click();
        $this->assertTrue($this->themerEditPage->header->logo->removeButton->displayed());

        // Remove the logo and verify that in the original theme there still is
        // a logo.
        $this->themerEditPage->header->logo->removeButton->click();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        $this->assertTrue($this->themerEditPage->header->logo->removeButton->displayed());
    }
}
