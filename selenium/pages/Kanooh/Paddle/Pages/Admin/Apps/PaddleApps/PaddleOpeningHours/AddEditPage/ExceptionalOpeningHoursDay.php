<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\ExceptionalOpeningHoursDay.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ExceptionalOpeningHoursDay.
 *
 * @property Text $startTime
 * @property Text $endTime
 * @property Text $description
 */
class ExceptionalOpeningHoursDay
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * @var int
     */
    public $weekday;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
        $this->weekday = $element->attribute('data-weekday');
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        $day = $this->getWeekday($this->weekday);

        switch ($name) {
            case 'startTime':
                return new Text($this->webdriver, $this->element->byName("field_ous_exc_opening_hours[und][0][field_ous_$day][und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]"));
                break;
            case 'endTime':
                return new Text($this->webdriver, $this->element->byName("field_ous_exc_opening_hours[und][0][field_ous_$day][und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]"));
                break;
            case 'description':
                return new Text($this->webdriver, $this->element->byName("field_ous_exc_opening_hours[und][0][field_ous_$day][und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]"));
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }

    /**
     * Gets the textual representation of the day we want.
     *
     * @param int $weekday
     *   The weekday identifier.
     *
     * @return string
     *   The textual representation of the day we want.
     */
    protected function getWeekday($weekday)
    {
        $weekdays = array(
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        );

        return $weekdays[$weekday];
    }
}
