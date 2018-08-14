<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage;

use Kanooh\Paddle\Apps\MegaDropdown;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Modal\ConfirmModal;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for Mega Dropdown Paddlet class.
 *
 * @property ConfigurePageTable $table
 *   The table containing the level-1 menu items and their mega dropdown CRUD links.
 * @property ConfigurePageCreateMegaDropdownModal $createModal
 *   The dialog that appears when creating a Mega Dropdown entity.
 * @property ConfirmModal $confirmModal
 *   The confirm dialog that appears when deleting a Mega Dropdown entity.
 */
class ConfigurePage extends ConfigurePageBase
{
    /**
     * Constructs a ConfigurePage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The interface to the Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new MegaDropdown);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'table':
                return new ConfigurePageTable($this->webdriver);
            case 'createModal':
                return new ConfigurePageCreateMegaDropdownModal($this->webdriver);
            case 'confirmModal':
                return new ConfirmModal($this->webdriver);
        }
        return parent::__get($property);
    }
}
