<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRichFooter\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRichFooter\ConfigurePage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Mega Dropdown Entity Edit page class.
 *
 * @property ConfigurePageDisplay $display
 *   The Panels display.
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class ConfigurePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddle-rich-footer/edit/%';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new ConfigurePageDisplay($this->webdriver);
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }
}
