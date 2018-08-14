<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthCalendarView.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a month calendar view content of a calendar pane.
 */
class MonthCalendarView
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new MonthCalendarView object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the content itself.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Fetches a cell representing a day in the calendar.
     *
     * @param int $day
     *   The timestamp of the calendar day we want to fetch.
     *
     * @return bool|\Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthCalendarCell
     *   False if no cell found, the cell otherwise.
     */
    public function getCellByDay($day)
    {
        $date = new \DateTime();
        $date->setTimestamp($day);
        $id = 'calendar_pane-' . $date->format('Y-m-d');

        try {
            $element = $this->element->byXPath('.//td[@id="' . $id . '"]');
            return new MonthCalendarCell($this->webdriver, $element);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Fetches all the days with events.
     *
     * @return array
     *   An array of cells.
     */
    public function getDaysWithEvents()
    {
        $criteria = $this->element->using('xpath')->value('.//td[contains(@class, "has-events")]');
        $elements = $this->element->elements($criteria);

        $cells = array();
        foreach ($elements as $element) {
            $cells[] = new MonthCalendarCell($this->webdriver, $element);
        }

        return $cells;
    }

    /**
     * Gets the day view which is shown
     *
     * @return mixed
     *   Returns the day view if found, false otherwise.
     */
    public function getDayView()
    {
        $criteria = $this->element->using('xpath')->value('//div[contains(@class, "view-display-id-day_view")]');
        $elements = $this->element->elements($criteria);

        if (!count($elements)) {
            return false;
        }

        return new DayView($this->webdriver, $this->element->element($criteria));
    }
}
