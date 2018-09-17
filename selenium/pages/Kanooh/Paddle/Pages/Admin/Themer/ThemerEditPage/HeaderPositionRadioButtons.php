<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\HeaderPositionRadioButtons
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the header position choices.
 *
 * @property RadioButton $standard
 * @property RadioButton $customized
 */
class HeaderPositionRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'standard':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="standard"]'));
                break;
            case 'customized':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="customized"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
