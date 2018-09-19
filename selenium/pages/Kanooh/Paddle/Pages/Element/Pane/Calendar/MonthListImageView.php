<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthListImageView.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

/**
 * Represents a month list image view content of a calendar pane.
 *
 * @property MonthListDay[] $days
 *   Array of ListViewEvent objects.
 */
class MonthListImageView
{
    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new MonthListImageView object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the content itself.
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'days':
                $days = array();
                $criteria = $this->element->using('xpath')->value('.//div[contains(@class, "month-list-view-image-day")]');
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
