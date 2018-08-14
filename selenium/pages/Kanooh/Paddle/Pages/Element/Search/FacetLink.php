<?php

/**
 * @file
 * Contains Kanooh\Paddle\Pages\Element\Search\FacetLink;
 */

namespace Kanooh\Paddle\Pages\Element\Search;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a faceted search link.
 *
 * @property int $itemCount
 *   The items indexed for this facet value.
 * @property int $value
 *   The indexed value matching this facet link.
 */
class FacetLink
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the facet.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new facet link.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form field.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magically retrieve link properties.
     *
     * @param string $name
     *   The name of the property.
     *
     * @return string
     *   The property value if the property is found.
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'itemCount':
                return (int) $this->element->attribute('data-item-count');
            case 'value':
                return $this->element->attribute('data-value');
        }
        throw new \Exception("The property $name is not present");
    }

    /**
     * Clicks the link or the checkbox, if available.
     */
    public function click()
    {
        $this->isCheckboxLink() ? $this->clickCheckbox() : $this->element->click();
    }

    /**
     * Click the checkbox associated to the link.
     */
    protected function clickCheckbox()
    {
        // All the facet links get unique ids.
        $link_id = $this->element->attribute('id');

        // Prepare an xpath using the link id. We have to do this as the
        // waitUntilElementIsPresent() wants a full xpath.
        $xpath = '//a[@id="' . $link_id . '"]/../input[@type="checkbox"]';

        // The checkbox is added with javascript after page load.
        // Take this in account when accessing the checkbox.
        $this->webdriver->waitUntilElementIsPresent($xpath);

        // We don't use Checkbox class as we just need to click the element,
        // without caring of check / uncheck status.
        $checkbox = $this->element->byXPath($xpath);
        $this->webdriver->moveto($checkbox);
        $checkbox->click();
    }

    /**
     * Checks if the current link is of type checkbox.
     *
     * Facetapi ships with two widgets: one is link only, and the other
     * adds a checkbox with javascript.
     *
     * @return bool
     *   If the link is of type checkbox.
     */
    public function isCheckboxLink()
    {
        $classes = explode(' ', trim($this->element->attribute('class')));

        return false !== array_search('facetapi-checkbox', $classes);
    }
}
