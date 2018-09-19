<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Calendar\ListViewEvent.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Calendar;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Represents list view content of a calendar pane.
 *
 * @property string $date
 *   The date of the event.
 * @property string $time
 *   The time of the event.
 * @property string $title
 *   The title of the event.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $link
 *   The link to the event page.
 * @property string $endTime
 *   The end time of the event.
 * @property string $startTime
 *   The start time of the event.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $featuredImage
 * @property string $singleDate
 */
class ListViewEvent
{
    /**
     * The HTML element of the content itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new ListViewEvent object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the slide itself.
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
            case 'date':
                $element = $this->element->byXPath('.//div[contains(@class, "day-name")]');
                return $element->text();
                break;
            case 'time':
                $xpath = './/div[contains(@class, "views-field-field-paddle-calendar-date-1")]';
                return $this->element->byXPath($xpath)->text();
                break;
            case 'title':
                return $this->link->text();
                break;
            case 'link':
                return $this->element->byXPath('.//div[contains(@class, "views-field-title")]//a');
                break;
            case 'startTime':
                $xpath = './/div[contains(@class, "views-field-field-paddle-calendar-date")]'
                    . '//span[@class="date-display-start"]';
                return $this->element->byXPath($xpath)->text();
                break;
            case 'endTime':
                $xpath = './/div[contains(@class, "views-field-field-paddle-calendar-date")]'
                    . '//span[@class="date-display-end"]';
                return $this->element->byXPath($xpath)->text();
                break;
            case 'featuredImage':
                $xpath = './/div[contains(@class, "views-field-field-paddle-featured-image")]//img';
                return $this->element->byXPath($xpath);
            case 'singleDate':
                $xpath = './/span[contains(@class, "date-display-single")]';
                return $this->element->byXPath($xpath)->text();
        }

        throw new \Exception("Property with name $property not defined");
    }
}
