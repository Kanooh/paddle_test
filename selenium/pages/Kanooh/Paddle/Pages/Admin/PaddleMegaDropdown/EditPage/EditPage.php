<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage\EditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The Mega Dropdown Entity Edit page class.
 *
 * @property EditPageDisplay $display
 *   The Panels display.
 * @property EditPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property EditPageLayoutMegaDropdownModal $layoutModal
 *   The change layout modal.
 */
class EditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddle-mega-dropdown/edit/%/%';

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
                return new EditPageDisplay($this->webdriver);
            case 'contextualToolbar':
                return new EditPageContextualToolbar($this->webdriver);
            case 'layoutModal':
                return new EditPageLayoutMegaDropdownModal($this->webdriver);
        }
        return parent::__get($property);
    }
}
