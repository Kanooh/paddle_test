<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedTable;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The configuration page for the OutgoingRSS paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property RSSFeedTable $feedTable
 *   The table of Outgoing RSS feeds.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_outgoing_rss/configure';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'feedTable':
                return new RSSFeedTable($this->webdriver, '//table[@id="outgoing-rss-feeds-list"]/tbody');
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $webdriver = $this->webdriver;
        $path = $this->path;
        $callable = new SerializableClosure(
            function () use ($webdriver, $path) {
                if (strpos($webdriver->url(), $path) !== false) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
        $this->webdriver->waitUntilElementIsDisplayed('//body');
    }
}
