<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\User\LoginPage\LoginForm.
 */

namespace Kanooh\Paddle\Pages\User\LoginPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the user login form.
 *
 * @property Text $name
 *   The name input field.
 * @property Text $pass
 *   The password input field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The submit button.
 */
class LoginForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return new Text($this->webdriver, $this->webdriver->byName('name'));
            case 'pass':
                return new Text($this->webdriver, $this->webdriver->byName('pass'));
            case 'submit':
                return $this->webdriver->byCss('input.form-submit');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
