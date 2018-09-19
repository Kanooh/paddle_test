<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileAddPage.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * Add user page.
 *
 * @property UserProfileRegistrationFormContextualToolbar $contextualToolbar
 * @property UserProfileRegistrationForm $form
 */
class UserProfileAddPage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/people/create';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new UserProfileRegistrationFormContextualToolbar($this->webdriver);
            case 'form':
                return new UserProfileRegistrationForm($this->webdriver, $this->webdriver->byId('user-register-form'));
        }

        return parent::__get($property);
    }
}
