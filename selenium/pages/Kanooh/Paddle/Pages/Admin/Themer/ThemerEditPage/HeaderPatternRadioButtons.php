<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\HeaderPatternRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons for the header patterns in the Paddle Themer.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $uploadImage
 *   The radio button to upload an image.
 */
class HeaderPatternRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'uploadImage':
                // This can't be done with a real radio button because that is
                // hidden for an image, so we need to click the label.
                return $this->webdriver->byXPath('//label[@for="edit-header-website-header-styling-sections-form-elements-header-image-background-pattern-upload-image"]');
        }
        throw new \Exception("The property $name is undefined.");
    }
}
