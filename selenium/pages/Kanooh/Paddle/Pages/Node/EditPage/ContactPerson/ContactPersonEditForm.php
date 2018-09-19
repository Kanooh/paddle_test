<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\ContactPerson;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Utilities\AjaxService;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;

/**
 * Class representing the contact person edit form.
 *
 * @property Text $firstName
 * @property Text $lastName
 * @property Text $organizationalUnitLevel1
 * @property Text $organizationalUnitLevel2
 * @property Text $organizationalUnitLevel3
 * @property Text $locationTitle
 * @property Text $addressStreet
 * @property Text $addressStreetNumber
 * @property Text $addressPostalCode
 * @property Text $addressCity
 * @property Select $addressCountry
 * @property Text $email
 * @property Text $function
 * @property Text $linkedin
 * @property Text $website
 * @property Text $yammer
 * @property Text $skype
 * @property Text $twitter
 * @property Text $mobilePhone
 * @property Text $officePhone
 * @property Text $office
 * @property AutoCompletedText $manager
 * @property ImageAtomField $photo
 * @property CompanyInformationTable $companyInformationTable
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $moreCompanyInformationButton
 */
class ContactPersonEditForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'firstName':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_first_name[und][0][value]'));
            case 'lastName':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_last_name[und][0][value]'));
            case 'organizationalUnitLevel1':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_ou_level_1[und][0][value]'));
            case 'organizationalUnitLevel2':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_ou_level_2[und][0][value]'));
            case 'organizationalUnitLevel3':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_ou_level_3[und][0][value]'));
            case 'locationTitle':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_address[und][0][name_line]'));
            case 'addressStreet':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_address[und][0][thoroughfare]'));
            case 'addressStreetNumber':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_address[und][0][premise]'));
            case 'addressPostalCode':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_address[und][0][postal_code]'));
            case 'addressCity':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_address[und][0][locality]'));
            case 'addressCountry':
                $id = $this->element->byXPath('//label[.="Country "]')->attribute('for');
                return new Select($this->webdriver, $this->element->byId($id));
            case 'email':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_email[und][0][email]'));
            case 'function':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_function[und][0][value]'));
            case 'office':
                return new Text($this->webdriver, $this->element->byName('field_cp_office[und][0][value]'));
            case 'linkedin':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_linkedin[und][0][value]'));
            case 'website':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_website[und][0][value]'));
            case 'yammer':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_yammer[und][0][value]'));
            case 'twitter':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_twitter[und][0][value]'));
            case 'skype':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_skype[und][0][value]'));
            case 'mobilePhone':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_mobile_office[und][0][value]'));
            case 'officePhone':
                return new Text($this->webdriver, $this->element->byName('field_paddle_cp_phone_office[und][0][value]'));
            case 'manager':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('field_paddle_cp_manager[und][0][target_id]'));
            case 'photo':
                $element = $this->element->byXPath('.//div/input[@name="field_paddle_featured_image[und][0][sid]"]/..');
                return new ImageAtomField($this->webdriver, $element);
            case 'companyInformationTable':
                return new CompanyInformationTable($this->webdriver, '//table[contains(@id, "field-paddle-cp-company-info-values")]');
            case 'moreCompanyInformationButton':
                return $this->element->byName('field_paddle_cp_company_info_add_more');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper method to add a new row to the company information table.
     *
     * @param int $index
     *   The index of the new organization row.
     */
    public function addOrganization($index = 0)
    {
        if (empty($index)) {
            $rows = $this->companyInformationTable->rows;
            $index = count($rows) + 1;
        }

        $this->webdriver->clickOnceElementIsVisible($this->moreCompanyInformationButton);
        $this->webdriver->waitUntilElementIsPresent('//div[contains(@id, "edit-field-paddle-cp-company-info-und-' . $index . '-field-cp-organisation")]');
    }
}
