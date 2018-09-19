<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;

/**
 * Performs configuration tests on the i18n paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * User session service.
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

        // Instantiate some classes to use in the test.
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->frontPage = new FrontPage($this);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests the configuration of the paddlet.
     */
    public function testConfiguration()
    {
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        // Check that only the enabled languages are indeed displayed as enabled.
        $this->assertEnabledLanguages();

        // Enable an additional language.
        $this->configurePage->form->enableBulgarian->check();

        // Save the configuration.
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->configurePage->checkArrival();

        // Check that the settings were saved.
        $this->assertEnabledLanguages();

        // Use Bulgarian as default
        $this->configurePage->form->defaultBulgarian->select();

        // disable all our default languages.
        $this->configurePage->form->enableDutch->uncheck();
        $this->configurePage->form->enableEnglish->uncheck();
        $this->configurePage->form->enableFrench->uncheck();
        $this->configurePage->form->enableGerman->uncheck();

        // Save the configuration.
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->configurePage->checkArrival();

        // Go to frontend and make sure that you are on the Bulgarian page.
        $this->frontPage->go();
        $this->byCssSelector('body.i18n-bg');

        // Set everything back to how is was.
        $this->configurePage->go();
        $this->configurePage->checkArrival();
        $this->configurePage->form->enableDutch->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        $this->configurePage->go();
        $this->configurePage->form->defaultDutch->select();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Checks that all the languages are correctly enabled/disabled.
     */
    protected function assertEnabledLanguages()
    {
        drupal_static_reset('language_list');
        $enabled_languages = i18n_language_list();
        $supported_languages = paddle_i18n_supported_languages();
        foreach ($supported_languages as $code => $language) {
            $is_default_lang = in_array($language, $enabled_languages);
            $this->assertEquals($is_default_lang, $this->configurePage->form->{'enable' . $language}->isChecked());
        }
    }
}
