<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\PaddleEmbedConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage;

use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageContextualToolbarBase;

/**
 * The contextual toolbar for the configure page of the embed paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreate
 *   The "Create widget" button.
 */
class ConfigurePageContextualToolbar extends ConfigurePageContextualToolbarBase
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
            'Create' => array(
                'title' => 'Create widget',
            ),
            'Deactivate' => array(
                'title' => 'Deactivate',
            ),
        );
    }
}
