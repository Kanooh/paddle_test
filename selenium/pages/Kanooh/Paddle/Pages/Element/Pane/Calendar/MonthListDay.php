<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthListDay.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

/**
 * Represents a single day in the month list view for the calendar pane.
 *
 * @property ListViewEvent[] $events
 *   The events available for that day.
 */
class MonthListDay extends Day
{
    /**
     * {@inheritDoc}
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

        return parent::__get($property);
    }
}
