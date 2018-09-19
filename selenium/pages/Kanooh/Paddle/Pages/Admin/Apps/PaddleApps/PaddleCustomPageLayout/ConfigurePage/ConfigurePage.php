<?php

/**
 * @file
 * Contains
 *     \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\ConfigurePage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Pages\Element\CustomPageLayout\CustomPageLayoutsTable;

/**
 * The configuration page for the XMLSiteMap paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property CustomPageLayoutsTable $layoutsTable
 *   The table which contains the links to all custom page layouts.
 */
class ConfigurePage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_custom_page_layout/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
                break;
            case 'layoutsTable':
                return new CustomPageLayoutsTable(
                    $this->webdriver,
                    '//form[@id="paddle-custom-page-layout-configuration-form"]//table/tbody'
                );
                break;
        }
        return parent::__get($property);
    }
}
