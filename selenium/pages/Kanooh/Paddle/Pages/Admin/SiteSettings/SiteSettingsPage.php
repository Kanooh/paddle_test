<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage.
 */

namespace Kanooh\Paddle\Pages\Admin\SiteSettings;

use Kanooh\Paddle\Pages\AdminPage;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\LanguageSwitcher\LanguageSwitcher;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * The Site Settings page.
 *
 * @property SiteSettingsPageContextualToolbar $contextualToolbar
 *   The contextual toolbar on the page.
 * @property Text $siteName
 *   The "Site name" text field.
 * @property AutoCompletedText $homePage
 *   The "Homepage" autocomplete field.
 * @property AutoCompletedText $accessDeniedPage
 *   The "403 Access denied page" autocomplete field.
 * @property AutoCompletedText $notFoundPage
 *   The "404 Not Found page" autocomplete field.
 * @property Text $siteEmail
 *   The "Site e-mail" text field.
 * @property MaintenanceModeRadioButtons $maintenanceModeRadios
 *   The radios buttons to set the maintenance mode.
 * @property Text $maintenanceModeMessage
 *   The "Maintenance mode message" text area.
 * @property Wysiwyg $noSearchResultsMessage
 *   The "No search results" text area.
 * @property ImageAtomField $defaultSharedImage
 *   The default image which is displayed when you share a page
 *   through social media.
 * @property LanguageSwitcher|null $languageSwitcher
 *   The language switcher block on the page. Null is absent.
 */
class SiteSettingsPage extends AdminPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/site-settings';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new SiteSettingsPageContextualToolbar($this->webdriver);
            case 'siteName':
                return new Text($this->webdriver, $this->webdriver->byName('site_name'));
            case 'homePage':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('site_frontpage'));
            case 'accessDeniedPage':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('site_403'));
            case 'notFoundPage':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('site_404'));
            case 'siteEmail':
                return new Text($this->webdriver, $this->webdriver->byName('site_mail'));
            case 'maintenanceModeRadios':
                $element = $this->webdriver->byId('edit-paddle-maintenance-mode');

                return new MaintenanceModeRadioButtons($this->webdriver, $element);
            case 'maintenanceModeMessage':
                return new Text($this->webdriver, $this->webdriver->byName('paddle_maintenance_mode_message'));
            case 'noSearchResultsMessage':
                return new Wysiwyg($this->webdriver, 'edit-no-results-on-search-value');
            case 'languageSwitcher':
                try {
                    // Pass the container as it is uncertain if the language
                    // switcher is an <ul> or <select>.
                    $container = $this->webdriver->byId('block-locale-language-content');

                    return new LanguageSwitcher($this->webdriver, $container);
                } catch (\Exception $e) {
                    return null;
                }
                break;
            case 'defaultSharedImage':
                return new ImageAtomField(
                    $this->webdriver,
                    $this->webdriver->byXPath('.//div/input[@name="default_shared_image"]/..')
                );
                break;
        }

        return parent::__get($property);
    }
}
