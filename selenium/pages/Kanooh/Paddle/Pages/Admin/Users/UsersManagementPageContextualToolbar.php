<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\User\UsersManagementPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Users;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The generic contextual toolbar for the users management page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonAdd
 */
class UsersManagementPageContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Add' => array(
                'title' => 'Create user',
            ),
        );

        return $buttons;
    }
}
