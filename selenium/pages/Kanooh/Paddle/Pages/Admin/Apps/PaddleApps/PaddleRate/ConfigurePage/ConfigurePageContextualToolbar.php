<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of the Rate paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
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
            'Save' => array(
                'title' => 'Save',
            ),
        );
    }
}
