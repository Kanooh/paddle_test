<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * The administration dashboard.
 *
 * @property UserProfileEditContextualToolbar $contextualToolbar
 *   The contextual toolbar for the user profile edit page.
 * @property UserProfileEditForm $form
 *   The form to set the user settings.
 */
class UserProfileEditPage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'user/%/edit';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new UserProfileEditContextualToolbar($this->webdriver);
            case 'form':
                return new UserProfileEditForm($this->webdriver, $this->webdriver->byId('user-profile-form'));
        }
        return parent::__get($property);
    }
}
