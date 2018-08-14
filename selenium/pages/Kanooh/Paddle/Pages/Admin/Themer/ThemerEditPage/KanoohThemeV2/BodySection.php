<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2\BodySection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\KanoohThemeV2;

use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BackgroundPatternRadioButtons;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\Section;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\FileField;

/**
 * Class for the Body section in the Paddle Themer form.
 *
 * @property BackgroundPatternRadioButtons $backgroundPatternRadios
 * @property FileField $backgroundImage
 * @property Checkbox $displayPaneTopAsH2
 */
class BodySection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'backgroundPatternRadios':
                $element = $this->webdriver->byXPath('//div[@id="paddle-style-plugin-instance-body-background"]//div[contains(@id, "paddle-style-background-pattern")]');
                return new BackgroundPatternRadioButtons($this->webdriver, $element);
            case 'backgroundImage':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[body_body_background_sections_form_elements_body_background_background_image]"]',
                    '//input[@name="body_body_background_sections_form_elements_body_background_background_image_upload_button"]',
                    '//input[@name="body_body_background_sections_form_elements_body_background_background_image_remove_button"]'
                );
            case 'displayPaneTopAsH2':
                $element = $this->webdriver->byXPath('.//div[@id="paddle-style-plugin-instance-display-pane-top-as-h2"]//input');
                return new Checkbox($this->webdriver, $element);
        }

        return parent::__get($name);
    }
}
