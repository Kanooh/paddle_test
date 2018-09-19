<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage\ConfigurePageRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMegaDropdown\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The table row element on the configuration page for Mega Dropdown Paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkCreate
 *   The "Create" link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The "Edit" link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The "Delete" link.
 */
class ConfigurePageRow extends Row
{
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath_selector;
    }

    /**
     * Magically provides all known links as properties.
     *
     * Properties that start with 'link', followed by the machine name of a
     * link. For example: $this->linkCancel.
     *
     * @param string $name
     *   A link machine name prepending with 'link'.
     *
     * @return array|\PHPUnit_Extensions_Selenium2TestCase_Element
     *   The matching link element object or array of link elements if multiple
     *   have the same class.
     */
    public function __get($name)
    {
        // If the property starts with 'link...' then return the matching action
        // link.
        if (strpos($name, 'link') === 0) {
            $linkName = substr($name, 4);
            $xpath = $this->xpathSelector . '//a[contains(@class,"action-' . strtolower($linkName) . '")]';
            $this->webdriver->waitUntilElementIsPresent($xpath);
            return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }
}
