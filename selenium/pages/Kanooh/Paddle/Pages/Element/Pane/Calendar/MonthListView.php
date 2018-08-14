<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthListView.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

/**
 * Represents a month list view content of a calendar pane.
 *
 * @property MonthListDay[] $days
 *   Array of ListViewEvent objects.
 */
class MonthListView
{
    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new MonthListView object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the content itself.
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
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
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'days':
                $days = array();
                $criteria = $this->element->using('xpath')->value('.//div[contains(@class, "month-list-view-day")]');
                $elements = $this->element->elements($criteria);
                foreach ($elements as $element) {
                    $days[] = new MonthListDay($element);
                }
                return $days;
                break;
        }

        throw new \Exception("Property with name $property not defined");
    }
}
