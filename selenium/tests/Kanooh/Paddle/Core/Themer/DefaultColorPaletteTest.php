<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\DefaultColorPaletteTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the default color palette for the default theme.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DefaultColorPaletteTest extends WebDriverTestCase
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
     * Tests if the default theme (VO Branding Theme) has a default color palette.
     *
     * This is a regression test for KANWEBS-2619.
     * @see https://one-agency.atlassian.net/browse/KANWEBS-2619
     *
     * @group regression
     * @group themer
     */
    public function testDefaultColorPalette()
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

        // Check that the first color palette is selected. All new themes
        // inherit the settings from the default theme, so this will also check
        // the default theme.
        $this->assertEquals('palette_a_light', $this->themerEditPage->branding->colorPaletteRadios->getSelected()->getValue());
    }
}
