<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\DayView.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a day view content of a calendar pane.
 *
 * @property ListViewEvent[]  $events
 *   Array of ListViewEvent objects.
 */
class DayView
{
    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a new MonthListView object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the content itself.
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
        $this->webdriver = $webdriver;
    }

    /**
     * Magic getter for properties.
     *
     * @param string $property
     *   The property you want to retrieve.
     *
     * @return mixed
     *   The property's value.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'events':
                $events = array();
                $criteria = $this->element->using('xpath')->value('.//div[contains(@class, "views-row")]');
                $elements = $this->element->elements($criteria);
                foreach ($elements as $element) {
                    $events[] = new ListViewEvent($element);
                }
                return $events;
                break;
        }
    }
}
