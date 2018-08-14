<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\CustomCssSection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\FileField;

/**
 * Class CustomCssSection.
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property FileField $cssFile
 *   The Custom CSS file input field.
 */
class CustomCssSection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'cssFile':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[custom_css_form_elements_custom_css_custom_css_file]"]',
                    '//input[@name="custom_css_form_elements_custom_css_custom_css_file_upload_button"]',
                    '//input[@name="custom_css_form_elements_custom_css_custom_css_file_remove_button"]'
                );
        }

        return parent::__get($name);
    }
}
