<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The Themer Overview page of the Paddle Themer module.
 *
 * @property ThemerOverviewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class ThemerOverviewPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/themes';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ThemerOverviewPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    public function theme($theme_name)
    {
        $this->checkPath();
        $xpath = '//div[@data-theme-name="' . $theme_name . '"]';
        $element = $this->webdriver->byXPath($xpath);

        return new Theme($element);
    }

    /**
     * Returns the active theme.
     *
     * @return Theme
     */
    public function getActiveTheme()
    {
        $xpath = '//div[contains(concat(" ", normalize-space(@class), " "), " paddle-themer-theme ") and contains(@class, "active")]';
        $element = $this->webdriver->byXPath($xpath);

        return new Theme($element);
    }

    /**
     * Returns the standard theme.
     *
     * @return Theme
     */
    public function getStandardTheme()
    {
        return $this->theme('vo_standard');
    }
}
