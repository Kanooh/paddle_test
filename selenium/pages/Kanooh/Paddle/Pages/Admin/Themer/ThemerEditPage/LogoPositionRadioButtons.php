<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\LogoPositionRadioButtons
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the logo position choices.
 *
 * @property RadioButton $left
 * @property RadioButton $center
 */
class LogoPositionRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'left':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="left"]'));
                break;
            case 'center':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="center"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
