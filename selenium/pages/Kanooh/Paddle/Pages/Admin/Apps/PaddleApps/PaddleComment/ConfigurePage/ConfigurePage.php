<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Comment paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ConfigureForm $form
 *   The main configuration form on the page.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_comment/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'form':
                return new ConfigureForm($this->webdriver, $this->webdriver->byId('paddle-comment-configuration-form'));
                break;
        }
        return parent::__get($property);
    }
}
