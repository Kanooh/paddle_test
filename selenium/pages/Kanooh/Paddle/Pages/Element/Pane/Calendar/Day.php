<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\Day
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

/**
 * Represents a single day in a view mode for the calendar pane.
 *
 * @property string $title
 *   The title of the day.
 */
class Day
{
    /**
     * The HTML element of the day itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new WeekDay object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the day itself.
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
            case 'title':
                $xpath = './/div[contains(@class, "day-name")]/span';
                $element = $this->element->byXPath($xpath);
                return $element->text();
                break;
        }

        throw new \Exception("Property with name $property not defined");
    }
}
