<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ColorPaletteRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons for the color palettes in the Paddle Themer.
 *
 * @property RadioButton $aLight
 *   The radio button to use color palette A light.
 * @property RadioButton $bLight
 *   The radio button to use color palette B light.
 */
class ColorPaletteRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'aLight':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@value = "palette_a_light"]')
                );
            case 'bLight':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('//input[@value = "palette_b_light"]')
                );
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
