<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\PaneStyleSubpaletteRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select the url type.
 *
 * @property RadioButton $subPalette0
 *   The radio button representing the first subpalette.
 * @property RadioButton $subPalette1
 *   The radio button representing the second subpalette.
 * @property RadioButton $subPalette2
 *   The radio button representing the third subpalette.
 * @property RadioButton $subPalette3
 *   The radio button representing the fourth subpalette.
 * @property RadioButton $subPalette4
 *   The radio button representing the fifth subpalette.
 * @property RadioButton $subPalette5
 *   The radio button representing the sixth subpalette.
 * @property RadioButton $subPalette6
 *   The radio button representing the seventh subpalette.
 * @property RadioButton $subPalette7
 *   The radio button representing the eight subpalette.
 */
class PaneStyleSubpaletteRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'subPalette0':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-0'));
            case 'subPalette1':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-1'));
            case 'subPalette2':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-2'));
            case 'subPalette3':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-3'));
            case 'subPalette4':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-4'));
            case 'subPalette5':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-5'));
            case 'subPalette6':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-6'));
            case 'subPalette7':
                return new RadioButton($this->webdriver, $this->element->byId('edit-color-subpalettes-paddle-color-subpalette-7'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
