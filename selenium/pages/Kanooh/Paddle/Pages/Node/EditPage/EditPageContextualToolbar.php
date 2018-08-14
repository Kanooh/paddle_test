<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the node edit page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *    The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *    The "Save" button.
 */
class EditPageContextualToolbar extends ContextualToolbar
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
