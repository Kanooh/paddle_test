<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\ConfigurePage;

use Kanooh\Paddle\Pages\Element\CustomCss\ContextTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Custom CSS paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 * @property ContextTable $contextTable
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_custom_css/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
                break;
            case 'contextTable':
                return new ContextTable($this->webdriver, '//table[@id="context-list"]');
                break;
        }

        return parent::__get($property);
    }
}
