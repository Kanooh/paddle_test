<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\DropdownButton.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A drop down button in a contextual toolbar.
 */
class DropdownButton extends Element
{

    public function __construct(WebDriverTestCase $webdriver, $xpathSelector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpathSelector;
    }

    /**
     * Get the 'parent' button which opens the dropdown when clicking it.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getButton()
    {
        return $this->webdriver->byXpath($this->xpathSelector . '/a[contains(@class,"contextual-dropdown")]');
    }

    /**
     * Get a button in the dropdown by its label.
     *
     * @param string $label
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getButtonInDropdown($label)
    {
        $xpath = $this->xpathSelector . '/div[contains(@class, "item-list")]//li/a/span[text()="' . $label . '"]';
        return $this->webdriver->byXpath($xpath);
    }
}
