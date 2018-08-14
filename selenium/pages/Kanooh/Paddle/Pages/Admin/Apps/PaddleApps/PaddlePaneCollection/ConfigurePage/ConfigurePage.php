<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\ConfigurePage;

use Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the Pane Collection paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 * @property PaneCollectionTable $paneCollectionTable
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_pane_collection/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'paneCollectionTable':
                return new PaneCollectionTable(
                    $this->webdriver,
                    '//form[@id="paddle-pane-collection-configuration-form"]//table/tbody'
                );
        }

        return parent::__get($property);
    }
}
