<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\InfoPage\InfoPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\InfoPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the info page of the organizational unit app.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDeactivate
 *   The "Deactivate" button.
 */
class InfoPageContextualToolbar extends ContextualToolbar
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
            'Deactivate' => array(
                'title' => 'Deactivate',
            ),
        );

        return $buttons;
    }
}
