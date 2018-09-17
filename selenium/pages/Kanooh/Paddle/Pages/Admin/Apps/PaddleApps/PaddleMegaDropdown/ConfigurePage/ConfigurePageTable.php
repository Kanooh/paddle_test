<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePageTable.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * The table element class on the configuration page for Mega Dropdown Paddlet.
 *
 */
class ConfigurePageTable extends Table
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//form[@id="paddle-mega-dropdown-settings-form"]//table[@id="mega-dropdown-configuration"]';

    /**
     * Finds a table row by the entity title in it.
     *
     * @todo Theoretically there can be multiple menu items with the same title. Currently this fact is ignored.
     *
     * @param string $title
     *   The name of the entity. It will be used in the xpath to find the entity rows (if we have multiple entities with
     *   the same name).
     *
     * @return NULL|ConfigurePageRow
     *   The row, or NULL if no matching title was found.
     */
    public function getEntityRowByTitle($title)
    {
        $row_xpath = $this->xpathSelector . '//tr/td[text()="' . $title . '"]/..';

        // Verify a matching row actually exists.
        try {
            $this->webdriver->element($this->webdriver->using('xpath')->value($row_xpath));
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return null;
        }

        return new ConfigurePageRow($this->webdriver, $row_xpath);
    }
}
