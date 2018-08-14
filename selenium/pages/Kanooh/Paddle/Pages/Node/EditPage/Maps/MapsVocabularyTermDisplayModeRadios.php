<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Maps
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Maps;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the display mode for a maps vocabulary term filter.
 *
 * @property RadioButton $list
 * @property RadioButton $dropdown
 * @property RadioButton $hidden
 */
class MapsVocabularyTermDisplayModeRadios extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'list':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="list"]'));
            case 'dropdown':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="dropdown"]'));
            case 'hidden':
                return new RadioButton($this->webdriver, $this->element->byXPath('.//input[@value="hidden"]'));
        }

        throw new RadioButtonNotDefinedException($name);
    }
}
