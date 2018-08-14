<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage\EditPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the Mega Dropdown Entity Edit Page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonChangeLayout
 *   The "Change Layout" button.
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
            'ChangeLayout' => array(
                'title' => 'Change layout',
            ),
        );

        return $buttons;
    }
}
