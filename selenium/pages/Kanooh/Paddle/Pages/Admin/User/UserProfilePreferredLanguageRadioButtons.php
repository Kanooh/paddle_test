<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfilePreferredLanguageRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select a user preferred language.
 *
 * @property RadioButton $english
 * @property RadioButton $dutch
 * @property RadioButton $french
 */
class UserProfilePreferredLanguageRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'english':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "en"]'));
            case 'dutch':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "nl"]'));
            case 'french':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value = "fr"]'));
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
