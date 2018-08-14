<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of the MailChimp paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonEditApiKey
 *   The button to edit the MailChimp API key.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateSignupForm
 *   The button to create new Signup forms.
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
            'EditApiKey' => array(
                'title' => 'API key',
            ),
            'CreateSignupForm' => array(
                'title' => 'Signup form',
            ),
        );
    }
}
