<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of the Paddle Social Media.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDeactivate
 *   The "Deactivate" button.
 */
class ConfigurePageContextualToolbar extends ContextualToolbar
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
            'Deactivate' => array(
                'title' => 'Deactivate',
            ),
        );

        return $buttons;
    }
}
