<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\Calendar.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class for a Panels pane with Ctools content type 'Calendar'.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextButton
 *   Button to the next month/week.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previousButton
 *   Button to the previous month/week.
 * @property string $periodTitle
 *   The title of the period displayed (month or week).
 * @property string $viewMode
 *   The view mode of the period displayed (month_calendar_view, month_list_view
 *   or week_list_view).
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $icalFeedLink
 *   The link element to the iCal feed related to that pane.
 */
class Calendar extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var CalendarPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a Calendar pane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);
        $this->contentType = new CalendarPanelsContentType($this->webdriver);
    }

    /**
     * Magically provides all known elements of the pane as properties.
     *
     * @param string $property
     *   A element machine name.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element|string|bool
     *   The requested element.
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'nextButton':
                $xpath = $this->getXPathSelectorByUuid() . '//li[contains(@class, "date-next")]/a';
                return $this->webdriver->byXPath($xpath);
                break;

            case 'previousButton':
                $xpath = $this->getXPathSelectorByUuid() . '//li[contains(@class, "date-prev")]/a';
                return $this->webdriver->byXPath($xpath);
                break;

            case 'periodTitle':
                $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "date-heading")]/h3';
                $element = $this->webdriver->byXPath($xpath);
                return $element->text();
                break;

            case 'viewMode':
                $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "view-calendar-pane")]';
                $view_element = $this->webdriver->byXPath($xpath);
                $classes = explode(' ', $view_element->attribute('class'));
                foreach ($classes as $class) {
                    if (strpos($class, 'view-display-id-') === 0) {
                        return str_replace('view-display-id-', '', $class);
                    }
                }

                return false;
                break;

            case 'icalFeedLink':
                $xpath = $this->getXPathSelectorByUuid() . '//a[contains(@class, "ical-feed")]';
                return $this->webdriver->byXPath($xpath);
                break;
        }

        throw new \Exception("Property with name $property not defined");
    }

    /**
     * Goes to the next period (month or week) and waits for the animation to
     * finish.
     */
    public function nextPeriod()
    {
        // Store the current title, as it will change when we navigate
        // to the next period.
        $current_title = $this->periodTitle;

        $this->nextButton->click();
        $this->waitUntilTitleChanged($current_title);
    }

    /**
     * Goes to the previous period (month or week) and waits for the animation to
     * finish.
     */
    public function previousPeriod()
    {
        // Store the current title, as it will change when we navigate
        // to the previous period.
        $current_title = $this->periodTitle;

        $this->previousButton->click();
        $this->waitUntilTitleChanged($current_title);
    }

    /**
     * Waits until the Calendar animation to the next or previous slide is done.
     *
     * @param string $current_title
     *   The current title of the pane.
     */
    public function waitUntilTitleChanged($current_title)
    {
        $calendar = $this;
        $callable = new SerializableClosure(
            function () use ($calendar, $current_title) {
                if ($calendar->periodTitle != $current_title) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Returns the calendar content of the pane.
     *
     * @return mixed
     *   An object representing the pane content.
     */
    public function getPaneContent()
    {
        $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "view-content")]';
        $element = $this->webdriver->byXPath($xpath);
        switch ($this->viewMode) {
            case 'month_list_view':
                return new MonthListView($element);
            case 'month_list_view_image':
                return new MonthListImageView($element);
            case 'month_calendar_view':
                return new MonthCalendarView($this->webdriver, $element);
            case 'week_list_view':
                return new WeekListView($element);
        }
    }
}
