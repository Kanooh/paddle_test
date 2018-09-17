<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\SiteSettingsTest.
 */

namespace Kanooh\Paddle\Core\Admin;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\PaddleMaintenancePage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Tests Site Settings Page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SiteSettingsTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentService;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * @var PaddleMaintenancePage
     */
    protected $paddleMaintenancePage;

    /**
     * @var SiteSettingsPage
     */
    protected $siteSettingsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->dashboardPage = new DashboardPage($this);
        $this->emailTestDataProvider = new EmailTestDataProvider($this);
        $this->paddleMaintenancePage = new PaddleMaintenancePage($this);
        $this->siteSettingsPage = new SiteSettingsPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentService = new ContentCreationService($this, $this->userSessionService);
        $this->appService = new AppService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Disable the Multilingual app if needed.
        $this->appService->disableApp(new Multilingual);
        if (module_exists('i18n_variable')) {
            module_disable(array('i18n_variable'));
        }
        // Also make sure we start with maintenance_mode disabled.
        variable_set('paddle_maintenance_mode', 0);
    }

    /**
     * Tests the Website info section on the Site Settings Page.
     */
    public function testWebsiteInfoSettings()
    {
        $original_values = array(
            'site_name' => variable_get('site_name', ''),
            'site_frontpage' => variable_get('site_frontpage', ''),
            'site_403' => variable_get('site_403', ''),
            'site_404' => variable_get('site_404', ''),
            'site_mail' => variable_get('site_mail', ''),
        );
        $this->userSessionService->login('SiteManager');

        // Go to the Site settings page.
        $this->dashboardPage->go();
        $this->dashboardPage->siteSettingsMenuBlock->links->linkSiteSettings->click();
        $this->siteSettingsPage->checkArrival();

        // Check that all the fields are required.
        $this->siteSettingsPage->siteName->clear();
        $this->siteSettingsPage->homePage->clear();
        $this->siteSettingsPage->accessDeniedPage->clear();
        $this->siteSettingsPage->notFoundPage->clear();
        $this->siteSettingsPage->siteEmail->clear();
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('Site name field is required.');
        $this->assertTextPresent('Active homepage field is required.');
        $this->assertTextPresent('403 (access denied) page field is required.');
        $this->assertTextPresent('404 (not found) page field is required.');
        $this->assertTextPresent('E-mail address field is required.');

        // Fill in the fields.
        $site_name = $this->alphanumericDataProvider->getValidValue();
        $this->siteSettingsPage->siteName->fill($site_name);

        $autocompletes = array(
          'homePage' => $this->contentService->createBasicPage(),
          'accessDeniedPage' => $this->contentService->createBasicPage(),
          'notFoundPage' => $this->contentService->createBasicPage(),
        );
        foreach ($autocompletes as $field => $nid) {
            $this->siteSettingsPage->{$field}->fill("node/$nid");
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilSuggestionCountEquals(1);
            $autocomplete->pickSuggestionByPosition(0);
        }

        // Make sure the email field accepts only valid e-mail values.
        $email = $this->alphanumericDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email);
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('The e-mail address ' . $email . ' is not valid.');

        $email = $this->emailTestDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email);

        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();
        $this->assertTextPresent('The configuration options have been saved.');
        // Reload variables that can be retrieved via variable_get() because
        // they could have been changed by a separate thread.
        // @see DrupalWebTestCase::refreshVariables()
        global $conf;
        $conf = variable_initialize();

        // Check that the values were save correctly.
        $this->assertEquals(variable_get('site_name', ''), $site_name);
        $this->assertEquals(variable_get('site_frontpage', ''), 'node/' . $autocompletes['homePage']);
        $this->assertEquals(variable_get('site_403', ''), 'node/' . $autocompletes['accessDeniedPage']);
        $this->assertEquals(variable_get('site_404', ''), 'node/' . $autocompletes['notFoundPage']);
        $this->assertEquals(variable_get('site_mail', ''), $email);

        // Check that the values are correctly displayed on the form.
        $this->siteSettingsPage->reloadPage();
        $this->assertEquals($this->siteSettingsPage->siteName->getContent(), $site_name);
        $this->assertContains('node/' . $autocompletes['homePage'], $this->siteSettingsPage->homePage->getContent());
        $this->assertContains('node/' . $autocompletes['accessDeniedPage'], $this->siteSettingsPage->accessDeniedPage->getContent());
        $this->assertContains('node/' . $autocompletes['notFoundPage'], $this->siteSettingsPage->notFoundPage->getContent());
        $this->assertEquals($this->siteSettingsPage->siteEmail->getContent(), $email);

        // Restore the original values as these are important.
        foreach ($original_values as $var_name => $value) {
            variable_set($var_name, $value);
            if (empty($value)) {
                variable_del($var_name);
            }
        }
        cache_clear_all();
    }

    /**
     * Tests the Maintenance mode section on the Site Settings Page.
     */
    public function testMaintenanceModeSettings()
    {
        // Remove the maintenance_mode_message in case it has been saved in a
        // previous test.
        variable_del('paddle_maintenance_mode_message');
        // Enable Paddle maintenance mode.
        variable_set('paddle_maintenance_mode', 1);
        // Clear the page cache in order to pick up new access restrictions.
        cache_clear_all();

        $default_maintenance_title = 'Website in onderhoud';
        $default_maintenance_message = 'Over enkele minuten is de website weer online. Bedankt voor je geduld.';

        $our_maintenance_message_parts = array(
            'Behind the scenes we are building a brand new website.',
            'Check back soon! Need more info? Paddle to <a href="http://kanooh.be">kanooh.be</a>!',
        );

        // Use url() as while the site is in maintenance mode PaddlePage::checkArrival()
        // is not going to work - it searches for body classes which are not there.
        $this->url('/');

        $this->assertTextPresent(strip_tags($our_maintenance_message_parts[0]));
        $this->assertTextPresent(strip_tags($our_maintenance_message_parts[1]));
        $this->assertTextNotPresent($default_maintenance_title);
        $this->assertTextNotPresent($default_maintenance_message);

        // Now log-in, go to the Site Settings page and check the default values.
        $this->userSessionService->login('SiteManager');
        $this->siteSettingsPage->go();
        $this->assertTrue($this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->isSelected());
        $our_maintenance_message = implode('<br>', $our_maintenance_message_parts);
        $this->assertEquals($our_maintenance_message, $this->siteSettingsPage->maintenanceModeMessage->getContent());

        // Change only the message at first and check that it appears for
        // anonymous users. Also add some XSS to test that it gets filtered.
        $message = $this->alphanumericDataProvider->getValidValue();
        $script = '<script>alert("BOOM");</script>';
        $this->siteSettingsPage->maintenanceModeMessage->fill($message . $script);
        $message .= strip_tags($script);
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('The configuration options have been saved.');

        // Use url() as NotLoggedInPage::checkArrival will try to find body class
        // 'body.not-logged-in' on the maintenance page which doesn't exist.
        $this->url('/user/logout');
        $this->waitUntilTextIsPresent($message);
        $this->assertTextNotPresent($default_maintenance_title);
        $this->assertTextNotPresent($default_maintenance_message);

        // Turn the maintenance mode off and check that it is indeed off.
        $this->userSessionService->login('SiteManager');
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->maintenanceModeRadios->disableMaintenanceMode->select();
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('The configuration options have been saved.');

        $this->userSessionService->logoutViaUI();
        // Refresh as Drupal doesn't pick up the maintenance mode at once.
        $this->refresh();
        $this->assertTextNotPresent($message);
        $this->assertTextNotPresent($default_maintenance_title);
        $this->assertTextNotPresent($default_maintenance_message);

        // And finally enable it again and check the front page.
        $this->userSessionService->login('SiteManager');
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->select();
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('The configuration options have been saved.');
        $this->url('/user/logout');

        // Refresh as Drupal doesn't pick up the maintenance mode at once.
        $this->refresh();
        $this->waitUntilTextIsPresent($message);

        // Revert to the default Paddle Maintenance Mode setting for Selenium
        // tests like profiles/paddle/post_install_selenium.sh does so tests
        // run after this test can still rely on that default.
        variable_set('paddle_maintenance_mode', 0);
    }
}
