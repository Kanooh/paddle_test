<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UserProfileEditContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\User;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The generic contextual toolbar for the user profile edit page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class UserProfileEditContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Save' => array(
                'title' => 'Save',
            ),
        );

        return $buttons;
    }
}
