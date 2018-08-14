<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\WeekListView.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

/**
 * Represents a week list view content of a calendar pane.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $days
 *   Days of the current week.
 * @property ListViewEvent[] $events
 *   Events of the current week.
 */
class WeekListView
{
    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new WeekListView object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the content itself.
     */
    public function __construct(
        \PHPUnit_Extensions_Selenium2TestCase_Element $element
    ) {
        $this->element = $element;
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

    /**
     * Get a distinct event by day and title.
     *
     * @param string $day
     *   The day of the week to get the element from.
     * @param string $title
     *   The title of the event.
     *
     * @return mixed
     *   The event if found, false otherwise.
     */
    public function getEventByDayAndTitle($day, $title)
    {
        $criteria = $this->element->using('xpath')
            ->value('.//div[contains(@class, "' . $day . '")]//div[contains(@class, "views-field-title")]//a[contains(text(), "' . $title . '")]/../../..');
        $elements = $this->element->elements($criteria);

        if (!count($elements)) {
            return false;
        }

        return new ListViewEvent($this->element->element($criteria));
    }

    /**
     * Get the number of events for a day.
     *
     * @param string $day
     *   The name of the day of the week to get the count of events for.
     *
     * @return int
     *   The number of events found.
     */
    public function getNumberOfEventsForDay($day)
    {
        $xpath = './/div[contains(@class, "' . $day . '")]//div[contains(@class, "views-row")]';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        $count = 0;
        if (count($elements)) {
            foreach ($elements as $element) {
                // Filter empty event rows. They are used to get the view to
                // display an empty day entry.
                if (strlen($element->text())) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Get the day objects for the current week.
     *
     * @return WeekDay[]|array
     *   The objects for the days found in an array keyed by the day title,
     *   false if no days found.
     */
    public function getDaysForWeek()
    {
        $xpath = './/div[contains(@class, "week-list-view-day")]';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        $days = array();
        foreach ($elements as $element) {
            $day = new WeekDay($element);
            $days[$day->title] = $day;
        }

        return $days;
    }
}
