<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\User\PasswordForm\PasswordForm.
 */

namespace Kanooh\Paddle\Pages\User\PasswordPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the user password form.
 *
 * @property Text $name
 *   The name input field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The submit button.
 */
class PasswordForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return new Text($this->webdriver, $this->webdriver->byName('name'));
            case 'submit':
                return $this->webdriver->byCss('input.form-submit');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
