<?php

/**
 * @file
 * Contains
 *     \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleXMLSiteMap\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleXMLSiteMap\ConfigurePage;

use Kanooh\Paddle\Pages\Element\XMLSiteMap\SiteMapLinksTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the XMLSiteMap paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property SiteMapLinksTable $linksTable
 *   The table which contains the links to all XML site maps.
 */
class ConfigurePage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_xml_sitemap/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
                break;
            case 'linksTable':
                return new SiteMapLinksTable($this->webdriver, '//form[@id="paddle-xml-sitemap-configuration-form"]//table/tbody');
                break;
        }
        return parent::__get($property);
    }
}
