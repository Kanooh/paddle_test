<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage;

use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the IncomingRSS paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property RSSFeedTable $feedTable
 *   The table of Incoming RSS feeds.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_incoming_rss/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'feedTable':
                return new RSSFeedTable(
                    $this->webdriver,
                    '//form[@id="paddle-incoming-rss-configuration-form"]//table/tbody'
                );
        }
        return parent::__get($property);
    }
}
