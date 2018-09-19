<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\FormBuilderContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The generic contextual toolbar for the Formbuilder pages.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class FormBuilderContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Back' => array(
                'title' => 'Back',
            ),
            'Save' => array(
                'title' => 'Save',
            ),
        );

        return $buttons;
    }
}
