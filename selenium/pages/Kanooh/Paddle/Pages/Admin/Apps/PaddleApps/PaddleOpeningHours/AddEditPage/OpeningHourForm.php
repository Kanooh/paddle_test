<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * The main form of the add/edit opening hour entities.
 *
 * @property OpeningHourAddEditPageContextualToolbar contextualToolbar
 * @property Text $title
 * @property Text $closingDaysDateStart1
 * @property Checkbox $showEndDate
 * @property Text $closingDaysDateEnd1
 * @property Text $closingDaysDescription1
 * @property Text $closingDaysDateStart2
 * @property Text $closingDaysDescription2
 * @property Text $mondayDescription
 * @property Text $tuesdayDescription
 * @property Text $wednesdayDescription
 * @property Text $thursdayDescription
 * @property Text $fridayDescription
 * @property Text $saturdayDescription
 * @property Text $sundayDescription
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $closingDaysAddButton
 * @property ExceptionalOpeningHoursTable $exceptionalOpeningHoursTable
 */
class OpeningHourForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'contextualToolbar':
                return new OpeningHourAddEditPageContextualToolbar($this->webdriver);
            case 'title':
                return new Text($this->webdriver, $this->element->byName('title'));
                break;
            case 'closingDaysDateStart1':
                return new Text($this->webdriver, $this->element->byName('field_ous_closing_days[und][0][field_ous_closing_days_date][und][0][value][date]'));
                break;
            case 'showEndDate':
                return new Checkbox($this->webdriver, $this->element->byName('field_ous_closing_days[und][0][field_ous_closing_days_date][und][0][show_todate]'));
                break;
            case 'closingDaysDateEnd1':
                return new Text($this->webdriver, $this->element->byName('field_ous_closing_days[und][0][field_ous_closing_days_date][und][0][value2][date]'));
                break;
            case 'closingDaysDescription1':
                return new Text($this->webdriver, $this->element->byName('field_ous_closing_days[und][0][field_ous_closing_description][und][0][value]'));
                break;
            case 'closingDaysDateStart2':
                return new Text($this->webdriver, $this->element->byName('field_ous_closing_days[und][1][field_ous_closing_days_date][und][0][value][date]'));
                break;
            case 'closingDaysDescription2':
                return new Text($this->webdriver, $this->element->byName('field_ous_closing_days[und][1][field_ous_closing_description][und][0][value]'));
                break;
            case 'closingDaysAddButton':
                return $this->element->byName('field_ous_closing_days_add_more');
                break;
            case 'mondayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_monday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'mondayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_monday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'tuesdayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_tuesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'tuesdayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_tuesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'wednesdayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_wednesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'wednesdayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_wednesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'thursdayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_thursday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'thursdayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_thursday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'fridayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_friday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'fridayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_friday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'saturdayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_saturday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'saturdayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_saturday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'sundayStartTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_sunday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value][time]'));
                break;
            case 'sundayEndTime':
                return new Text($this->webdriver, $this->element->byName('field_ous_sunday[und][0][field_ous_opening_hours][und][0][field_ous_ou_hours][und][0][value2][time]'));
                break;
            case 'mondayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_monday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'tuesdayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_tuesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'wednesdayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_wednesday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'thursdayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_thursday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'fridayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_friday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'saturdayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_saturday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'sundayDescription':
                return new Text($this->webdriver, $this->element->byName('field_ous_sunday[und][0][field_ous_opening_hours][und][0][field_ous_ou_description][und][0][value]'));
              break;
            case 'exceptionalOpeningHoursTable':
                return new ExceptionalOpeningHoursTable($this->webdriver);
        }
        throw new FormFieldNotDefinedException($name);
    }
}
