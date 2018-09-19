<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\SearchPage\PaddleSearchPageForm.
 */

namespace Kanooh\Paddle\Pages\SearchPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the search form in the paddle search page.
 *
 * @package Kanooh\Paddle\Pages\SearchPage
 *
 * @property Text $keywords
 *   The search keywords text field.
 * @property RadioButton $defaultSearchRadioButton
 *   The radio button used to search with the default search engine.
 * @property RadioButton $googleSearchRadioButton
 *   The radio button used to search with Google search engine.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The submit button.
 */
class PaddleSearchPageForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'keywords':
                return new Text($this->webdriver, $this->element->byClassName('form-text'));
            case 'defaultSearchRadioButton':
                return new RadioButton($this->webdriver, $this->element->byId('edit-search-method-default-search'));
                break;
            case 'googleSearchRadioButton':
                return new RadioButton($this->webdriver, $this->element->byId('edit-search-method-google-custom'));
                break;
            case 'submit':
                return $this->element->byXPath('.//input[@type="submit"]');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
