<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\SiteSettingsMenuTest.
 */

namespace Kanooh\Paddle\Core\Admin;

use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests Site Settings Menu.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SiteSettingsMenuTest extends WebDriverTestCase
{
    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->dashboardPage = new DashboardPage($this);
        $this->userSessionService = new UserSessionService($this);

        $this->userSessionService->login('SiteManager');

        $drupal = new DrupalService();
        $drupal->bootstrap($this);
    }

    /**
     * Tests the Site Settings Menu.
     */
    public function testSiteSettingsMenu()
    {
        $this->dashboardPage->go();

        // Test the Site settings link.
        $settings_url = $this->dashboardPage->siteSettingsMenuBlock->links->linkSiteSettings->attribute('href');
        $this->assertStringEndsWith('site-settings', $settings_url);

        // Test the sitename link.
        $site_name = $this->dashboardPage->siteSettingsMenuBlock->links->linkSiteName->text();
        $this->assertEquals(variable_get('site_name', ''), $site_name);
        $front_page_url = $this->dashboardPage->siteSettingsMenuBlock->links->linkSiteName->attribute('href');
        $this->assertEquals(url('<front>', array('absolute' => true)), $front_page_url);

        // Test the HelpDesk link.
        $helpdesk_url = $this->dashboardPage->siteSettingsMenuBlock->links->linkHelpDesk->attribute('href');
        $this->assertEquals(variable_get('paddle_helpdesk_url', ''), $helpdesk_url);

        // Test that the HelpDesk link will be opened in a new tab.
        $this->assertEquals(
            '_blank',
            $this->dashboardPage->siteSettingsMenuBlock->links->linkHelpDesk->attribute('target')
        );
    }
}
