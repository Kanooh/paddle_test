<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageContextualToolbarBase.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of paddlets.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDeactivate
 *   The "Deactivate" button.
 */
class ConfigurePageContextualToolbarBase extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        return array(
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
    }
}
