<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileRegistrationForm.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\TargetGroupRadios;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Class representing the user registration form.
 *
 * @property Text $userName
 * @property Text $email
 * @property Text $password
 * @property Text $confirmPassword
 * @property Checkbox $notify
 * @property Checkboxes $roles
 * @property Text $submit
 * @property LanguageSettingsRadios $languageSettings
 */
class UserProfileRegistrationForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'userName':
                return new Text($this->webdriver, $this->element->byName('name'));
            case 'email':
                return new Text($this->webdriver, $this->element->byName('mail'));
            case 'password':
                return new Text($this->webdriver, $this->element->byName('pass[pass1]'));
            case 'confirmPassword':
                return new Text($this->webdriver, $this->element->byName('pass[pass2]'));
            case 'notify':
                return new Checkbox($this->webdriver, $this->element->byName('notify'));
            case 'roles':
                return new Checkboxes($this->webdriver, $this->element->byId('edit-roles'));
            case 'submit':
                return new Text($this->webdriver, $this->element->byName('op'));
            case 'languageSettings':
                return new LanguageSettingsRadios($this->webdriver, $this->element->byXPath('.//div[contains(@id, "edit-admin-language")]'));
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper function to complete the required fields on the user registration form.
     */
    public function completeUserProfile()
    {
        $alphanumeric_test_data_provider = new AlphanumericTestDataProvider();
        if ($this->userName->getContent() == '') {
            $real_name = $alphanumeric_test_data_provider->getValidValue();
            $this->userName->fill($real_name);
        }
        if ($this->email->getContent() == '') {
            $email = $alphanumeric_test_data_provider->getValidValue(8, true, true) . '@kanooh.be';
            $this->email->fill($email);
        }
        $this->password->fill('demo');
        $this->confirmPassword->fill('demo');
    }
}
