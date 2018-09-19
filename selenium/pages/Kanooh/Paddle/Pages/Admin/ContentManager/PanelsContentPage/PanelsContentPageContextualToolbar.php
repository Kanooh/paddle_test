<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * Class LandingPagePanelsContentPageContextualToolbar
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonChangeLayout
 *   The "Change layout" button.
 */
class PanelsContentPageContextualToolbar extends ContextualToolbar
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
