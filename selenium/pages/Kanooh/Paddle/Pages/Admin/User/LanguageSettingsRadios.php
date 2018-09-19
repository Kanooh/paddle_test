<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Use\LanguageSettingsRadios
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the language settings choices.
 *
 * @property RadioButton $dutch
 * @property RadioButton $english
 * @property RadioButton $french
 */
class LanguageSettingsRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'dutch':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="nl"]'));
                break;
            case 'english':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="en"]'));
                break;
            case 'french':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="fr"]'));
                break;
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
