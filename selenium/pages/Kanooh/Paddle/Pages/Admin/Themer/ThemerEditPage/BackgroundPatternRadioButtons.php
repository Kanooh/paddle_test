<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BackgroundPatternRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons for the background patterns in the Paddle Themer.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $uploadImage
 *   The radio button to upload an image.
 */
class BackgroundPatternRadioButtons extends RadioButtons
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
                $criteria = $this->element->using('xpath')->value('.//label[contains(@for, "background-background-pattern-upload-image")]');
                $elements = $this->element->elements($criteria);

                if (count($elements)) {
                    return $elements[0];
                }
        }
        throw new \Exception("The property $name is undefined.");
    }
}
