<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\MultilingualService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\WebDriver\WebDriverTestCase;

class MultilingualService
{
    /**
     * Protected MultilingualService constructor so the class can't be instantiated.
     */
    protected function __construct()
    {
    }

    /**
     * Sets only one language as default and disables all the other languages.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $language
     *   The human name of the language to set as default and only enabled.
     */
    public static function setDefaultLanguage(WebDriverTestCase $webdriver, $language)
    {
        if (language_default('name') == $language) {
            // The language is already default.
            return;
        }

        $configure_page = new ConfigurePage($webdriver);

        // Set Dutch as default.
        $configure_page->go();
        $configure_page->form->{'default' . $language}->select();
        $configure_page->contextualToolbar->buttonSave->click();
        $webdriver->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Disable all enabled languages. The default will not be disabled because
     * the checkbox cannot be checked.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public static function disableAllNonDefaultLanguages(WebDriverTestCase $webdriver)
    {
        drupal_static_reset('language_list');
        $enabled_languages = i18n_language_list();
        if (count($enabled_languages) == 1) {
            // All languages except the default one are disabled.
            return;
        }

        $configure_page = new ConfigurePage($webdriver);

        // Disable all languages.
        $configure_page->go();
        foreach ($enabled_languages as $code => $language_name) {
            $configure_page->form->{'enable' . $language_name}->uncheck();
        }
        $configure_page->contextualToolbar->buttonSave->click();
        $webdriver->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Enables the languages passed.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param array $languages
     *   Array of the human names of the languages to enable, like "English".
     */
    public static function enableLanguages(WebDriverTestCase $webdriver, $languages)
    {
        drupal_static_reset('language_list');
        $enabled_languages = array_values(i18n_language_list());
        if (!array_diff($languages, $enabled_languages)) {
            // All these languages are already enabled.
            return;
        }

        $configure_page = new ConfigurePage($webdriver);

        $configure_page->go();
        foreach ($languages as $language) {
            $configure_page->form->{'enable' . $language}->check();
        }
        $configure_page->contextualToolbar->buttonSave->click();
        $webdriver->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Reset the default language.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver.
     * @param string $default_language
     *   The default language to set.
     */
    public static function setBackDefaultLanguage(WebDriverTestCase $webdriver, $default_language = 'Dutch')
    {
        $configure_page = new ConfigurePage($webdriver);
        $configure_page->go();
        $configure_page->form->{'default' . $default_language}->select();
        $configure_page->contextualToolbar->buttonSave->click();
        $webdriver->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Set the default Multilingual settings as expected by the Paddle Multilingual tests.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public static function setPaddleTestDefaults(WebDriverTestCase $webdriver)
    {
        $languages = array('English', 'French', 'German', 'Dutch');
        drupal_static_reset('language_list');
        $enabled_languages = array_values(i18n_language_list());

        // Make sure that if other languages are enabled we disable them.
        if (array_diff($enabled_languages, $languages)) {
            self::disableAllNonDefaultLanguages($webdriver);
        }

        // Make sure that these 3 languages + Dutch are enabled.
        self::enableLanguages($webdriver, $languages);
        self::setBackDefaultLanguage($webdriver);
    }

    /**
     * Finds the current language prefix from the url.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     *
     * @return string
     *   The language prefix found, empty string otherwise.
     */
    public static function getLanguagePathPrefix(WebDriverTestCase $webdriver)
    {
        // Bootstrap Drupal just to be sure it is.
        $drupal_service = new DrupalService();
        $drupal_service->bootstrap($webdriver);

        $language_prefix = '';
        if (function_exists('i18n_language_list')) {
            // Ignore query string and all other URL parts except the path.
            $current_path = parse_url($webdriver->path(), PHP_URL_PATH);
            // Strip forward slash at the beginning and end, if any because we
            // don't want those to generate empty components.
            $current_path = trim($current_path, '/');
            $current_path_components = explode('/', $current_path);
            drupal_static_reset('language_list');
            $enabled_languages = i18n_language_list();
            if (isset($enabled_languages[$current_path_components[0]])) {
                $language_prefix = $current_path_components[0];
            }
        }

        return $language_prefix;
    }

    /**
     * Determines whether the site is multilingual or not.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     *
     * @return bool
     *   Whether the site is multilingual or not.
     */
    public static function isMultilingual(WebDriverTestCase $webdriver)
    {
        // Bootstrap Drupal so we can use its API, such as module_exists().
        $drupal_service = new DrupalService();
        $drupal_service->bootstrap($webdriver);

        return module_exists('paddle_i18n');
    }
}
