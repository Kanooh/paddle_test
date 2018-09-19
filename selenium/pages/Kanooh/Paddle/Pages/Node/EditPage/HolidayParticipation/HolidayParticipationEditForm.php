<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\HolidayParticipationEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Utilities\AjaxService;

/**
 * Class representing the HolidayParticipation edit form.
 *
 * @property Wysiwyg $body
 * @property Text $facebook
 * @property Text $twitter
 * @property Text $youtube
 * @property Text $website
 * @property Text $svpContactId
 * @property Select $category
 * @property Text $seo_title
 * @property Text $title
 * @property Select $province
 * @property Select $contractType
 * @property Text $baseContractId
 * @property Checkboxes $roomBoard
 * @property Checkbox $formula
 * @property Text $min_capacity
 * @property Text $max_capacity
 * @property Text $zipcode
 * @property Text $city
 * @property Text $street
 * @property Checkboxes $labels
 * @property Checkboxes $facilities
 * @property Text $contractStartYear
 * @property Text $contractEndYear
 * @property Text $validityPeriodStart
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addPlaceButton
 * @property PlacesTable $placesTable
 */
class HolidayParticipationEditForm extends Form
{
    /**
     * {@inheritdoc}
     */

    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return new Text($this->webdriver, $this->element->byName('title'));
                break;
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-body-und-0-value');
                break;
            case 'category':
                return new Select($this->webdriver, $this->element->byName('field_hp_category[und]'));
                break;
            case 'facebook':
                return new Text($this->webdriver, $this->element->byName('field_hp_facebook[und][0][value]'));
                break;
            case 'twitter':
                return new Text($this->webdriver, $this->element->byName('field_hp_twitter[und][0][value]'));
                break;
            case 'website':
                return new Text($this->webdriver, $this->element->byName('field_hp_website[und][0][value]'));
                break;
            case 'youtube':
                return new Text($this->webdriver, $this->element->byName('field_hp_youtube[und][0][value]'));
                break;
            case 'svpContactId':
                return new Text($this->webdriver, $this->element->byName('field_hp_svp_contract_id[und][0][value]'));
                break;
            case 'seo_title':
                return new Text($this->webdriver, $this->element->byName('field_paddle_seo_title[und][0][value]'));
                break;
            case 'province':
                return new Select($this->webdriver, $this->element->byName('field_hp_province[und]'));
                break;
            case 'contractType':
                return new Select($this->webdriver, $this->element->byName('field_hp_contract_type[und]'));
                break;
            case 'baseContractId':
                return new Text($this->webdriver, $this->element->byName('field_hp_base_contract_id[und][0][value]'));
                break;
            case 'roomBoard':
                return new Checkboxes($this->webdriver, $this->element->byId('edit-field-hp-room-and-board'));
                break;
            case 'formula':
                return new Checkbox($this->webdriver, $this->element->byName('field_hp_formula_oh[und]'));
                break;
            case 'min_capacity':
                return new Text($this->webdriver, $this->element->byName('field_hp_min_capacity[und][0][value]'));
                break;
            case 'max_capacity':
                return new Text($this->webdriver, $this->element->byName('field_hp_max_capacity[und][0][value]'));
                break;
            case 'city':
                return new Text($this->webdriver, $this->element->byName('field_hp_address[und][0][locality]'));
                break;
            case 'zipcode':
                return new Text($this->webdriver, $this->element->byName('field_hp_address[und][0][postal_code]'));
                break;
            case 'street':
                return new Text($this->webdriver, $this->element->byName('field_hp_address[und][0][thoroughfare]'));
                break;
            case 'labels':
                return new Checkboxes($this->webdriver, $this->element->byId('edit-field-hp-labels'));
                break;
            case 'facilities':
                return new Checkboxes($this->webdriver, $this->element->byId('edit-field-hp-facilities'));
                break;
            case 'contractStartYear':
                return new Text($this->webdriver, $this->element->byName('field_hp_contract_start_year[und][0][value]'));
                break;
            case 'contractEndYear':
                return new Text($this->webdriver, $this->element->byName('field_hp_contract_end_year[und][0][value]'));
                break;
            case 'validityPeriodStart':
                return new Text($this->webdriver, $this->element->byName('field_hp_validity_period[und][0][value][date]'));
                break;
            case 'addPlaceButton':
                return $this->element->byName('field_hp_places_add_more');
                break;
            case 'placesTable':
                return new PlacesTable($this->webdriver, '//table[contains(@id, "field-hp-places-values")]');
                break;
        }

        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper method to add a new row to the places table.
     */
    public function addPlace()
    {
        $this->webdriver->moveto($this->addPlaceButton);
        $rows = $this->placesTable->rows;
        $number_of_rows = count($rows) + 1;
        $ajax_service = new AjaxService($this->webdriver);
        $ajax_service->markAsWaitingForAjaxCallback($this->addPlaceButton);
        $this->addPlaceButton->click();
        $this->webdriver->waitUntilElementIsPresent('//div[contains(@id, "edit-field-hp-places-und-' . $number_of_rows . '-field-hp-target-group")]');
    }
}
