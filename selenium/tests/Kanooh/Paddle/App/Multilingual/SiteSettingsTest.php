<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\SiteSettingsTest.
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs Multilingual Site Settings tests.
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
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentService;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * The original site settings.
     *
     * @var array
     */
    protected $original_values;

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
        parent::setUpPage();

        // Create some instances to use later on.
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->emailTestDataProvider = new EmailTestDataProvider($this);
        $this->siteSettingsPage = new SiteSettingsPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService->login('SiteManager');

        // Enable the Multilingual app.
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);

        // Collect the original variables of the site settings.
        $this->original_values = array(
            'site_name' => variable_get('site_name', ''),
            'site_frontpage' => variable_get('site_frontpage', ''),
            'site_403' => variable_get('site_403', ''),
            'site_404' => variable_get('site_404', ''),
            'site_mail' => variable_get('site_mail', ''),
        );
    }

    /**
     * Tests the translating of Site Settings values.
     */
    public function testSiteSettingsTranslation()
    {
        $this->siteSettingsPage->go();

        // On the default language, fill in the site settings.
        // Fill in the fields.
        $site_name_nl = $this->alphanumericDataProvider->getValidValue();
        $this->siteSettingsPage->siteName->fill($site_name_nl);

        $pages_nl = array(
            'homePage' => $this->contentService->createBasicPage('homePage'),
            'accessDeniedPage' => $this->contentService->createBasicPage('accessDeniedPage'),
            'notFoundPage' => $this->contentService->createBasicPage('notFoundPage'),
        );
        foreach ($pages_nl as $field => $nid) {
            $this->siteSettingsPage->{$field}->fill("node/$nid");
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilSuggestionCountEquals(1);
            $autocomplete->pickSuggestionByPosition(0);
        }

        $email_nl = $this->emailTestDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email_nl);

        // Save the site settings.
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();

        // Reload variables that can be retrieved via variable_get() because
        // they could have been changed by a separate thread.
        // @see DrupalWebTestCase::refreshVariables()
        global $conf;
        $conf = variable_initialize();

        // Switch to English on the language switcher.
        $this->siteSettingsPage->languageSwitcher->switchLanguage('en');

        // Assert that the values are by default the default language
        // site settings.
        $this->assertSettings($site_name_nl, $email_nl, $pages_nl);

        // Fill in the English terms.
        $site_name_en = $this->alphanumericDataProvider->getValidValue();
        $this->siteSettingsPage->siteName->fill($site_name_en);

        $pages_en = array(
            'homePage' => $this->contentService->createBasicPage('homePage'),
            'accessDeniedPage' => $this->contentService->createBasicPage('accessDeniedPage'),
            'notFoundPage' => $this->contentService->createBasicPage('notFoundPage'),
        );
        foreach ($pages_en as $field => $nid) {
            // Change the page its language to English.
            $this->contentService->changeNodeLanguage($nid, 'en');

            $this->siteSettingsPage->{$field}->fill("node/$nid");
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilSuggestionCountEquals(1);
            $autocomplete->pickSuggestionByPosition(0);
        }

        $email_en = $this->emailTestDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email_en);

        // Assert that the values are now the English
        // site settings.
        $this->assertSettings($site_name_en, $email_en, $pages_en);

        // Switch back to the default language.
        $this->siteSettingsPage->languageSwitcher->switchLanguage('nl');

        // Assert that the values are still the ones you set in Dutch.
        $this->assertSettings($site_name_nl, $email_nl, $pages_nl);

        // Restore the original values as these are important.
        foreach ($this->original_values as $var_name => $value) {
            variable_set($var_name, $value);
            if (empty($value)) {
                variable_del($var_name);
            }
        }
        cache_clear_all();
    }

    /**
     * Tests the language switcher on the Site Settings page.
     */
    public function testSiteSettingsLanguageSwitcher()
    {
        // Assert that the default language is NL.
        $this->siteSettingsPage->go();
        $default_language = $this->siteSettingsPage->languageSwitcher->getActiveLanguage();
        $this->assertEquals('nl', $default_language);

        // Assert that the language switcher disappears when you uninstall Multilingual.
        $app = new Multilingual;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        $this->siteSettingsPage->go();
        $this->assertNull($this->siteSettingsPage->languageSwitcher);
    }

    /**
     * Tests that the maintenance mode can only be set on the default language.
     */
    public function testSiteSettingsDisabledMaintenanceOption()
    {
        // Assert that the maintenance mode button is enabled on the default language (nl).
        $this->siteSettingsPage->go();
        $this->assertTrue($this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->isEnabled());
        $this->assertTrue($this->siteSettingsPage->maintenanceModeRadios->disableMaintenanceMode->isEnabled());

        // Assert that the maintenance mode is disabled on all other languages.
        $this->siteSettingsPage->languageSwitcher->switchLanguage('en');
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->isEnabled());
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->disableMaintenanceMode->isEnabled());
        $this->siteSettingsPage->languageSwitcher->switchLanguage('fr');
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->isEnabled());
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->disableMaintenanceMode->isEnabled());
        $this->siteSettingsPage->languageSwitcher->switchLanguage('de');
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->enableMaintenanceMode->isEnabled());
        $this->assertFalse($this->siteSettingsPage->maintenanceModeRadios->disableMaintenanceMode->isEnabled());
    }

    /**
     * Asserts the Site Settings values.
     *
     * @param string $site_name
     *   The name of the site.
     * @param string $email
     *   The e-mail address of the site.
     * @param array $pages
     *   The home, 404 and 403 page nodes.
     */
    protected function assertSettings($site_name, $email, $pages)
    {
        $this->assertEquals($site_name, $this->siteSettingsPage->siteName->getContent());
        $this->assertEquals($email, $this->siteSettingsPage->siteEmail->getContent());

        foreach ($pages as $field => $nid) {
            $this->assertEquals("$field (node/$nid)", $this->siteSettingsPage->{$field}->getContent());
        }
    }
}
