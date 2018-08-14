<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * Class representing the display mode for a vocabulary term filter.
 *
 * @property RadioButton $list
 * @property RadioButton $dropdown
 * @property RadioButton $hidden
 */
class VocabularyTermDisplayModeRadios extends RadioButtons
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
