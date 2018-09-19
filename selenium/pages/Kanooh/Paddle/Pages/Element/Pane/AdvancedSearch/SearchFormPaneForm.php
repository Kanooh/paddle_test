<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchFormPaneForm.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the form in the advanced search form pane.
 *
 * @property Text $keywords
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 */
class SearchFormPaneForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'keywords':
                return new Text($this->webdriver, $this->element->byName('search'));
            case 'submit':
                return $this->element->byXPath('.//input[@type="submit"]');
        }

        throw new FormFieldNotDefinedException($name);
    }
}
