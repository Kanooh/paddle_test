<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\FrontEndPaddlePageSearchForm.
 */

namespace Kanooh\Paddle\Pages;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the search form on a front end page.
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
class FrontEndPaddlePageSearchForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'keywords':
                return new Text($this->webdriver, $this->webdriver->byCss('#search-api-page-search-form-search .form-text'));
            case 'defaultSearchRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-search-method-default-search'));
                break;
            case 'googleSearchRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-search-method-google-custom'));
                break;
            case 'submit':
                return $this->webdriver->byCss('#search-api-page-search-form-search input[type="submit"]');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Waits for the search form to be hidden.
     *
     * There is a delay on the form being hidden due to CSS animations.
     */
    public function waitUntilHidden()
    {
        $form = $this;
        $callable = new SerializableClosure(
            function () use ($form) {
                if (!$form->keywords->isDisplayed() && !$form->submit->displayed()) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Waits for the search form to be visible.
     *
     * There is a delay on the form being visible due to CSS animations.
     */
    public function waitUntilVisible()
    {
        $form = $this;
        $callable = new SerializableClosure(
            function () use ($form) {
                if ($form->keywords->isDisplayed() && $form->submit->displayed()) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
