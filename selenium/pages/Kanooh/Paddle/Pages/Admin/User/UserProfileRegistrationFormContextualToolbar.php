<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileRegistrationFormContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The generic contextual toolbar for the user registration page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 */
class UserProfileRegistrationFormContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Save' => array(
                'title' => 'Create new account',
            ),
        );

        return $buttons;
    }
}
