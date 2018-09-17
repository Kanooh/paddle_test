<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\CompanyInformationTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\ContactPerson;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class CompanyInformationTableRow
 *
 * @property AutoCompletedText $organizationalUnit
 * @property AutoCompletedText $manager
 * @property Text $function
 * @property Text $email
 * @property Text $phone
 * @property Text $mobile
 * @property Checkbox $loadContactInfo
 * @property Text $street
 * @property Text $street_number
 * @property Text $postal_code
 * @property Text $city
 * @property Text $office
 * @property Text $url
 */
class CompanyInformationTableRow extends Row
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
            case 'organizationalUnit':
                $element = $this->element->byCssSelector('div.field-name-field-cp-organisation input');
                return new AutoCompletedText($this->webdriver, $element);
            case 'manager':
                $element = $this->element->byCssSelector('div.field-name-field-cp-manager input');
                return new AutoCompletedText($this->webdriver, $element);
            case 'function':
                $element = $this->element->byCssSelector('div.field-name-field-cp-function input');
                return new Text($this->webdriver, $element);
            case 'email':
                $element = $this->element->byCssSelector('div.field-name-field-cp-email input');
                return new Text($this->webdriver, $element);
            case 'phone':
                $element = $this->element->byCssSelector('div.field-name-field-cp-phone input');
                return new Text($this->webdriver, $element);
            case 'mobile':
                $element = $this->element->byCssSelector('div.field-name-field-cp-mobile input');
                return new Text($this->webdriver, $element);
            case 'office':
                $element = $this->element->byCssSelector('div.field-name-field-cp-office input');
                return new Text($this->webdriver, $element);
            case 'url':
                $element = $this->element->byCssSelector('div.field-name-field-cp-url input');
                return new Text($this->webdriver, $element);
            case 'loadContactInfo':
                $element = $this->element->byCssSelector('div.field-name-field-cp-load-contact-info input');
                return new Checkbox($this->webdriver, $element);
            case 'street':
                $element = $this->element->byCssSelector('div.field-name-field-cp-ou-address input.thoroughfare');
                return new Text($this->webdriver, $element);
            case 'street_number':
                $element = $this->element->byCssSelector('div.field-name-field-cp-ou-address input.premise');
                return new Text($this->webdriver, $element);
            case 'postal_code':
                $element = $this->element->byCssSelector('div.field-name-field-cp-ou-address input.postal-code');
                return new Text($this->webdriver, $element);
            case 'city':
                $element = $this->element->byCssSelector('div.field-name-field-cp-ou-address input.locality');
                return new Text($this->webdriver, $element);
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
