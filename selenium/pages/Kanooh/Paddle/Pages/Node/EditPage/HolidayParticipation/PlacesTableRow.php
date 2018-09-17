<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\PlacesTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PlacesTableRow
 *
 * @property TargetGroupRadios $targetGroup
 * @property Text $birthYearMin
 * @property Text $birthYearMax
 * @property ReservationStateRadios $reservationState
 * @property Text $capacitySvp
 * @property Text $theme
 * @property Text $location
 * @property Text $periodDateStart
 * @property Text $periodDateEnd
 * @property Checkbox $transportOffered
 * @property Checkbox $internal
 */
class PlacesTableRow extends Row
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'targetGroup':
                $element = $this->element->byXPath('//div[contains(@id, "field-hp-target-group-und")]');
                return new TargetGroupRadios($this->webdriver, $element);
                break;
            case 'birthYearMin':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_birth_year_min]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'birthYearMax':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_birth_year_max]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'reservationState':
                $element = $this->element->byXPath('//div[contains(@id, "field-hp-reservation-state-und")]');
                return new ReservationStateRadios($this->webdriver, $element);
                break;
            case 'capacitySvp':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_capacity_svp]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'theme':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_theme]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'location':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_location]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'periodDateStart':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_period_date][und][0][value][date]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'periodDateEnd':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_period_date][und][0][value2][date]")]');
                return new Text($this->webdriver, $element);
                break;
            case 'transportOffered':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_transport_offered]")]');
                return new Checkbox($this->webdriver, $element);
                break;
            case 'internal':
                $element = $this->element->byXPath('//input[contains(@name, "[field_hp_internal]")]');
                return new Checkbox($this->webdriver, $element);
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
