<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Node\TranslatePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the node translate page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *    The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *    The "Save" button.
 */
class TranslatePageContextualToolbar extends ContextualToolbar
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
