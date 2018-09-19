<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\SearchBoxRadioButtons.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\RadioButtonNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButtons;

/**
 * The radio buttons that allow to select to use or not use the annotation filters.
 *
 * @property RadioButton $paddleSearch
 *   The radio button to use the internal search.
 * @property RadioButton $googleCustomSearch
 *   The radio button to use google custom search.
 */
class SearchBoxRadioButtons extends RadioButtons
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'paddleSearch':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="default_search"]')
                );
            case 'googleCustomSearch':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="google_custom"]')
                );
        }
        throw new RadioButtonNotDefinedException($name);
    }
}
