<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage;

use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageContextualToolbarBase;

/**
 * The contextual toolbar for the configure page of the Contact Person paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
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
        );
    }
}
