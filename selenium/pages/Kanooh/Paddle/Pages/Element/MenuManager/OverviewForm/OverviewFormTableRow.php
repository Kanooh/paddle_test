<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm\OverviewFormTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row in the Menu Overview Form.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkShowChildItems
 *   The link to show the child menu items.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEditMenuItem
 *   The link to edit the menu item.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDeleteMenuItem
 *   The link to delete the menu item.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTableDrag
 *   The link to change the placement of the menu item.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $menuItemWeight
 *   The input or select containing the weight of the menu item.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $title
 *   The text identifying the menu item.
 */
class OverviewFormTableRow extends Row
{

    /**
     * Constructs an OverviewFormTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
    }

    /**
     * Magically provides all known elements of the row as properties.
     *
     * @param string $name
     *   An element machine name.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The requested element.
     *
     * @throws ElementNotPresentException
     *   Thrown when the requested form field is not defined.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'linkShowChildItems':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "paddle-big-menu-expandable")]');
            case 'linkEditMenuItem':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "ui-icon-edit")]');
            case 'linkDeleteMenuItem':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "ui-icon-delete")]');
            case 'linkTableDrag':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "tabledrag-handle")]');
            case 'menuItemWeight':
                return $this->webdriver->byXPath($this->xpathSelector . '//*[contains(@class, "menu-weight")]');
            case 'title':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "tabledrag-handle")]/..');
        }
        throw new ElementNotPresentException($name);
    }

    /**
     * Focus the table drag link on that row.
     */
    public function focusTableDrag()
    {
        $this->webdriver->execute(
            array(
                'script' => "document.querySelector('tr.mlid-" . $this->getMlid() . " a.tabledrag-handle').focus();",
                'args' => array(),
            )
        );
    }

    /**
     * Retrieves a mlid using the row object.
     *
     * @return int | null
     *   The id of the menu item represented by this row or null if mlid cannot
     *   be determined.
     */
    public function getMlid()
    {
        $row = $this->getWebdriverElement();
        $classes = explode(' ', $row->attribute('class'));
        foreach ($classes as $class) {
            if (strpos($class, 'mlid-') === 0) {
                return str_replace('mlid-', '', $class);
            }
        }
        return null;
    }

    /**
     * Waits until the child items of the current table row are present.
     */
    public function waitUntilChildItemsArePresent()
    {
        $child_xpath = '/../tr[contains(@class, "paddle-big-menu-parent-mlid-' . $this->getMlid() . '")]';
        $this->webdriver->waitUntilElementIsPresent($this->xpathSelector . $child_xpath);
    }

    /**
     * Checks if the child items are shown.
     *
     * @return bool
     *    True if the child menu items are shown, false otherwise.
     */
    public function childItemsShown()
    {
        return in_array('paddle-big-menu-unfolded', explode(' ', $this->linkShowChildItems->attribute('class')));
    }

    /**
     * Checks if the current menu item is marked as disabled or not.
     *
     * @return bool
     *   True if the current menu item is disabled, false otherwise.
     */
    public function menuItemDisabled()
    {
        return strpos($this->title->text(), '(disabled)') !== false;
    }
}
