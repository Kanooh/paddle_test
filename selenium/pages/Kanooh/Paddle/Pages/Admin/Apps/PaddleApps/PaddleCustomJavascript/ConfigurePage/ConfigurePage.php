<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomJavascript\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomJavascript\ConfigurePage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Custom Javascript paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 * @property ConfigureForm $form
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_custom_javascript/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'form':
                return new ConfigureForm($this->webdriver, $this->webdriver->byId('paddle-custom-javascript-configuration-form'));
                break;
        }
        return parent::__get($property);
    }
}
