<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\MenuStyleRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select which menu style to use.
 *
 * @property RadioButton $noMenuStyle
 *   The radio button to not use a menu style.
 * @property RadioButton $flyOutMenu
 *   The radio button to use the fly out menu.
 * @property RadioButton $megaDropdown
 *   The radio button to use the mega dropdown.
 */
class MenuStyleRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'noMenuStyle':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "no_dropdown"]'));
            case 'flyOutMenu':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "fly_out"]'));
            case 'megaDropdown':
                return new RadioButton($this->webdriver, $this->element->byXPath('//input[@value = "mega_dropdown"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
