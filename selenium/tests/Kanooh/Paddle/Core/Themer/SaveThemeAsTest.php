<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\SaveThemeAsTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SaveThemeAsTest extends WebDriverTestCase
{

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

        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

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
     * Tests the "Save as" button.
     *
     * See https://one-agency.atlassian.net/browse/KANWEBS-2834.
     *
     * @group themer
     * @group regression
     */
    public function testSaveAsButton()
    {
        // Create a new theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Click "Save as" button.
        $this->themerEditPage->buttonSaveAs->click();

        // Wait for the "Save as" page to load.
        $this->waitUntilTextIsPresent('Theme name');

        // Regression test. There was an error message here.
        $this->assertTextNotPresent('Invalid argument supplied');

        // Submit the page to see if we get redirected to the Theme Overview page.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
    }
}
