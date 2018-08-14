<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\HeaderSection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class HeaderSection.
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property Checkbox $paddleSearchEnabled
 * @property Text $paddleSearchTitle
 * @property Checkbox $googleCustomSearchEnabled
 * @property Text $googleCustomSearchTitle
 * @property Checkbox $showLogoInHeader
 * @property FileField $logo
 * @property MenuStyleRadioButtons $menuStyleOptions
 * @property Checkbox $standardSearchPlaceholderTextEnabled
 * @property Checkbox $searchBoxPopUpEnabled
 * @property Text $standardSearchPlaceholderText
 * @property Checkbox $standardSearchButtonLabelEnabled
 * @property Text $standardSearchButtonLabel
 * @property BackgroundPatternRadioButtons $backgroundPatternRadios
 * @property FileField $backgroundImage
 * @property HeaderPatternRadioButtons $headerPatternRadios
 * @property FileField $headerImage
 * @property HeaderPositionRadioButtons $headerPosition
 * @property LogoPositionRadioButtons $logoPosition
 * @property NavigationPositionRadioButtons $navigationPosition
 * @property Checkbox $stickyHeader
 */
class HeaderSection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'paddleSearchEnabled':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][default_search_enabled]')
                );
            case 'paddleSearchTitle':
                return new Text(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][default_search_text]')
                );
            case 'googleCustomSearchEnabled':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][google_custom_enabled]')
                );
            case 'googleCustomSearchTitle':
                return new Text(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][google_custom_text]')
                );
            case 'standardSearchPlaceholderTextEnabled':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][search_placeholder_text_checkbox]')
                );
            case 'standardSearchPlaceholderText':
                return new Text(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][search_placeholder_text]')
                );
            case 'standardSearchButtonLabelEnabled':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][search_placeholder_button_label_checkbox]')
                );
            case 'standardSearchButtonLabel':
                return new Text(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_box_options][search_placeholder_button_label]')
                );
            case 'searchBoxPopUpEnabled':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[search_box][sections][form_elements][search_placeholder_popup_checkbox][search_placeholder_popup_checkbox]')
                );
            case 'showLogoInHeader':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[website_header][sections][form_elements][branding_logo][header_show_logo]')
                );
            case 'logo':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[header_website_header_sections_form_elements_branding_logo_logo]"]',
                    '//input[@name="header_website_header_sections_form_elements_branding_logo_logo_upload_button"]',
                    '//input[@name="header_website_header_sections_form_elements_branding_logo_logo_remove_button"]'
                );
            case 'menuStyleOptions':
                return new MenuStyleRadioButtons($this->webdriver, $this->webdriver->byId('edit-header-menu-style-sections-form-elements-menu-style-menu-style'));
            case 'backgroundPatternRadios':
                $element = $this->webdriver->byXPath('//div[@id="paddle-style-plugin-instance-header-background"]//div[@id="paddle-style-background-pattern"]');
                return new BackgroundPatternRadioButtons($this->webdriver, $element);
            case 'backgroundImage':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[header_website_header_styling_sections_form_elements_header_background_background_image]"]',
                    '//input[@name="header_website_header_styling_sections_form_elements_header_background_background_image_upload_button"]',
                    '//input[@name="header_website_header_styling_sections_form_elements_header_background_background_image_remove_button"]'
                );
            case 'headerPatternRadios':
                $element = $this->webdriver->byXPath('//div[@id="paddle-style-plugin-instance-header-image"]//div[@id="paddle-style-background-pattern--2"]');
                return new HeaderPatternRadioButtons($this->webdriver, $element);
            case 'headerImage':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[header_website_header_styling_sections_form_elements_header_image_background_image]"]',
                    '//input[@name="header_website_header_styling_sections_form_elements_header_image_background_image_upload_button"]',
                    '//input[@name="header_website_header_styling_sections_form_elements_header_image_background_image_remove_button"]'
                );
            case 'headerPosition':
                $element = $this->element->byXPath('.//div[contains(@id, "edit-header-header-positioning-sections-form-elements-header-positioning-header-position")]');
                return new HeaderPositionRadioButtons($this->webdriver, $element);
                break;
            case 'logoPosition':
                $element = $this->element->byXPath('.//div[contains(@id, "edit-header-header-positioning-sections-form-elements-header-positioning-position-fields-logo")]');
                return new LogoPositionRadioButtons($this->webdriver, $element);
                break;
            case 'navigationPosition':
                $element = $this->element->byXPath('.//div[contains(@id, "edit-header-header-positioning-sections-form-elements-header-positioning-position-fields-navigation")]');
                return new NavigationPositionRadioButtons($this->webdriver, $element);
                break;
            case 'stickyHeader':
                return new Checkbox(
                    $this->webdriver,
                    $this->element->byName('header[header_positioning][sections][form_elements][header_positioning][position_fields][sticky_header]')
                );
        }

        return parent::__get($name);
    }
}
