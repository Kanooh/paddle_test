<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileEditForm.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Class representing the user edit profile form.
 *
 * @property Text $realName
 * @property Text $userName
 * @property Text $email
 * @property Text $phoneNumber
 * @property FileField $profilePicture
 * @property Checkboxes $roles
 * @property Text $currentPassword
 * @property Checkboxes $notifications
 * @property  UserProfilePreferredLanguageRadioButtons $language
 */
class UserProfileEditForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'realName':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_user_real_name[und][0][value]'));
            case 'userName':
                return new Text($this->webdriver, $this->webdriver->byName('name'));
            case 'email':
                return new Text($this->webdriver, $this->webdriver->byName('mail'));
            case 'phoneNumber':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_user_telephone[und][0][value]'));
            case 'profilePicture':
                return new FileField($this->webdriver, '//input[@name="files[picture_upload]"]');
            case 'roles':
                return new Checkboxes($this->webdriver, $this->element->byId('edit-roles'));
            case 'currentPassword':
                return new Text($this->webdriver, $this->webdriver->byName('current_pass'));
            case 'language':
                $xpath = './/div[contains(@class, "form-radios") and contains(@id, "edit-admin-language")]';
                return new UserProfilePreferredLanguageRadioButtons($this->webdriver, $this->element->byXPath($xpath));
            case 'notifications':
                return new Checkboxes(
                    $this->webdriver,
                    $this->webdriver->byXPath('//div[@id="edit-field-paddle-user-notifications-und"]')
                );
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper function to complete the required fields on the user profile.
     */
    public function completeUserProfile()
    {
        $alphanumeric_test_data_provider = new AlphanumericTestDataProvider();
        if ($this->realName->getContent() == '') {
            $real_name = $alphanumeric_test_data_provider->getValidValue();
            $this->realName->fill($real_name);
        }
        if ($this->email->getContent() == '') {
            $email = $alphanumeric_test_data_provider->getValidValue(8, true, true) . '@kanooh.be';
            $this->email->fill($email);
        }
        $this->currentPassword->fill('demo');
    }
}
