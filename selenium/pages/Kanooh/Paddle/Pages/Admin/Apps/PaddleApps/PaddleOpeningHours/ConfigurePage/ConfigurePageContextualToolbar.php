<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of the opening hours paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonAdd
 *   The button to add a new opening hour.
 */
class ConfigurePageContextualToolbar extends ContextualToolbar
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
            'Add' => array(
                'title' => 'Add opening hours set',
            ),
        );
    }
}
