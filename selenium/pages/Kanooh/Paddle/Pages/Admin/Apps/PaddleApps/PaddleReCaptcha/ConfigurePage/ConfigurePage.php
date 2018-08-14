<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the ReCaptcha paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 * @property ConfigureForm $form
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_recaptcha/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
                break;
            case 'form':
                return new ConfigureForm($this->webdriver, $this->webdriver->byId('paddle-recaptcha-configuration-form'));
                break;
        }

        return parent::__get($property);
    }
}
