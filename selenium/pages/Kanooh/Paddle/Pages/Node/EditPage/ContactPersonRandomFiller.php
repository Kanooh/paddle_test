<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\TestDataProvider\UrlTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Fills in the contact person node edit form.
 */
class ContactPersonRandomFiller
{

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * @var UrlTestDataProvider
     */
    protected $urlTestDataProvider;

    /**
     * @var string
     */
    public $firstName;

    /**
     * The first name.
     *
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $organizationalUnitLevel1;

    /**
     * @var string
     */
    public $organizationalUnitLevel2;

    /**
     * @var string
     */
    public $organizationalUnitLevel3;

    /**
     * @var string
     */
    public $locationTitle;

    /**
     * @var string
     */
    public $addressStreet;

    /**
     * @var string
     */
    public $addressStreetNumber;

    /**
     * @var string
     */
    public $addressPostalCode;

    /**
     * @var string
     */
    public $addressCity;

    /**
     * @var string
     */
    public $addressCountry;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $fax;

    /**
     * @var string
     */
    public $function;

    /**
     * @var string
     */
    public $skype;

    /**
     * @var string
     */
    public $linkedin;

    /**
     * @var string
     */
    public $twitter;

    /**
     * @var string
     */
    public $website;

    /**
     * @var string
     */
    public $yammer;

    /**
     * @var string
     */
    public $mobilePhone;

    /**
     * @var string
     */
    public $officePhone;

    /**
     * @var string
     */
    public $office;

    /**
     * @var string
     */
    public $manager;

    /**
     * @var string
     */
    public $ou_title;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->emailTestDataProvider = new EmailTestDataProvider();
        $this->urlTestDataProvider = new UrlTestDataProvider();
    }

    /**
     * Randomize the fields of the form.
     *
     * @return $this
     *   Returns the filler.
     */
    public function randomize()
    {
        $this->firstName = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->lastName = $this->alphanumericTestDataProvider->getValidValue(16);
        // Make the body longer than 600 characters so we can verify trimming.
        $this->body = $this->alphanumericTestDataProvider->getValidValue(626);
        $this->organizationalUnitLevel1 = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->organizationalUnitLevel2 = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->organizationalUnitLevel3 = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->locationTitle = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->addressStreet = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->addressStreetNumber = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->addressPostalCode = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->addressCity = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->addressCountry = 'BE';
        $this->email = $this->emailTestDataProvider->getValidValue();
        $this->fax = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->mobilePhone = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->officePhone = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->office = $this->alphanumericTestDataProvider->getValidValue(16);

        // At the moment, we only test Belgian address fields.
        $this->function = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->linkedin = $this->urlTestDataProvider->getValidValue();
        $this->twitter = $this->urlTestDataProvider->getValidValue();
        $this->website = $this->urlTestDataProvider->getValidValue();
        $this->yammer = $this->urlTestDataProvider->getValidValue();
        $this->skype = $this->alphanumericTestDataProvider->getValidValue(16);

        return $this;
    }

    /**
     * Fills the form fields for a specific page.
     *
     * @param ContactPersonEditPage $page
     *   The page for which to form the fields.
     * @param WebDriverTestCase $webDriver
     */
    public function fill(ContactPersonEditPage $page, WebDriverTestCase $webDriver = null)
    {
        $page->form->firstName->fill($this->firstName);
        $page->form->lastName->fill($this->lastName);
        $page->body->setBodyText($this->body);
        $page->form->linkedin->fill($this->linkedin);
        $page->form->twitter->fill($this->twitter);
        $page->form->yammer->fill($this->yammer);
        $page->form->skype->fill($this->skype);

        if (!module_exists('paddle_organizational_unit')) {
            $page->form->organizationalUnitLevel1->fill($this->organizationalUnitLevel1);
            $page->form->organizationalUnitLevel2->fill($this->organizationalUnitLevel2);
            $page->form->organizationalUnitLevel3->fill($this->organizationalUnitLevel3);
            $page->form->locationTitle->fill($this->locationTitle);
            $page->form->addressCity->fill($this->addressCity);
            $page->form->addressPostalCode->fill($this->addressPostalCode);
            $page->form->addressStreet->fill($this->addressStreet);
            $page->form->addressStreetNumber->fill($this->addressStreetNumber);
            $page->form->email->fill($this->email);
            $page->form->function->fill($this->function);
            $page->form->mobilePhone->fill($this->mobilePhone);
            $page->form->officePhone->fill($this->officePhone);
            $page->form->office->fill($this->office);
            $page->form->website->fill($this->website);
        } elseif (!empty($this->ou_title) && isset($webDriver)) {
            $page->form->addOrganization();
            $row = $page->form->companyInformationTable->rows[0];
            $row->organizationalUnit->fill($this->ou_title);
            $row->organizationalUnit->waitForAutoCompleteResults();
            $auto_complete = new AutoComplete($webDriver);
            $auto_complete->waitUntilDisplayed();
            // This move to a higher field prevents the sticky button bar to be in front of
            // the autocomplete.
            $webDriver->moveto($page->form->firstName->getWebdriverElement());
            $auto_complete->pickSuggestionByPosition(0);
            $row->loadContactInfo->uncheck();

            // Here new fields are loaded after the uncheck, so we will assert one of the labels to be present.
            $webDriver->waitUntilTextIsPresent(t('URL'));
            $row->street->fill($this->addressStreet);
            $row->city->fill($this->addressCity);
            $row->postal_code->fill($this->addressPostalCode);
            $row->street_number->fill($this->addressStreetNumber);
            $row->email->fill($this->email);
            $row->function->fill($this->function);
            $row->mobile->fill($this->mobilePhone);
            $row->phone->fill($this->officePhone);
            $row->office->fill($this->office);
            $row->url->fill($this->website);
        }
    }

    /**
     * Set the organizational unit title.
     *
     * @param string $ou_title
     *   The title of the eventual OU which is linked to the contact person.
     */
    public function setOrganizationalUnitTitle($ou_title)
    {
        $this->ou_title = $ou_title;
    }
}
