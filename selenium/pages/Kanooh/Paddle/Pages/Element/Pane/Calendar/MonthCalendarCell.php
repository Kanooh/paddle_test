<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthCalendarCell.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a single cell in a month calendar view table.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $dayLink
 *   The link for a day if it has events.
 */
class MonthCalendarCell extends Element
{
    /**
     * The webdriver element of the table cell.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new MonthCalendarCell.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element for the cell.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magically provides all known elements of the element as properties.
     *
     * @param string $property
     *   A element machine name.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|string|bool
     *   The requested element.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'dayLink':
                return $this->element->byXPath('.//div[contains(@class, "mini-day-on")]//a');
                break;
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $property, E_USER_NOTICE);
    }

    /**
     * Checks if a day has events.
     *
     * @return bool
     *   True if the day has any events, false otherwise.
     */
    public function hasEvents()
    {
        $classes = explode(' ', $this->element->attribute('class'));

        return (bool) array_search('has-events', $classes);
    }
}
