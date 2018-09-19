<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\NavigationPositionRadioButtons
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the navigation position choices.
 *
 * @property RadioButton $right
 * @property RadioButton $center
 */
class NavigationPositionRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'right':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="right"]'));
                break;
            case 'center':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="center"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
