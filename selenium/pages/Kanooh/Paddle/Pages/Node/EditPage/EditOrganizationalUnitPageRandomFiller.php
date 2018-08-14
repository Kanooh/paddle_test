<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPageRandomFiller.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\TestDataProvider\UrlTestDataProvider;

/**
 * Fills in the organizational unit node edit form.
 */
class EditOrganizationalUnitPageRandomFiller
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
    public $unitName;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $seoDescription;

    /**
     * @var string
     */
    public $logo;

    /**
     * @var string
     */
    public $headOfUnit;

    /**
     * @var string
     */
    public $locationName;

    /**
     * @var string
     */
    public $locationStreet;

    /**
     * @var string
     */
    public $locationStreetNumber;

    /**
     * @var string
     */
    public $locationPostalCode;

    /**
     * @var string
     */
    public $locationCity;

    /**
     * @var string
     */
    public $locationCountry;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $fax;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $website;

    /**
     * @var string
     */
    public $twitter;

    /**
     * @var string
     */
    public $linkedin;

    /**
     * @var string
     */
    public $facebook;

    /**
     * @var string
     */
    public $vatNumber;

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
        // Generate a name using only alphanumeric characters. If a name would
        // start with a period or contain the character sequence '/.' it will
        // generate a 403 error due to a known bug in Drupal and the test will
        // fail.
        // @see https://drupal.org/node/1232134
        $this->unitName = $this->alphanumericTestDataProvider->getValidValue(16);
        // Make the body longer than 600 characters so we can verify trimming.
        $this->body = $this->alphanumericTestDataProvider->getValidValue(626);
        $this->seoDescription = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->headOfUnit = $this->alphanumericTestDataProvider->getValidValue();
        $this->locationName = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->locationStreet = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->locationStreetNumber = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->locationPostalCode = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->locationCity = $this->alphanumericTestDataProvider->getValidValue(16);
        // At the moment, we only test Belgian address fields.
        $this->locationCountry = 'BE';
        $this->phone = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->fax = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->email = $this->emailTestDataProvider->getValidValue();
        $this->website = $this->urlTestDataProvider->getValidValue();
        $this->facebook = $this->urlTestDataProvider->getValidValue();
        $this->twitter = $this->urlTestDataProvider->getValidValue();
        $this->linkedin = $this->urlTestDataProvider->getValidValue();
        $this->vatNumber = $this->alphanumericTestDataProvider->getValidValue(8);

        return $this;
    }

    /**
     * Fills the form fields for a specific page.
     *
     * @param EditOrganizationalUnitPage $page
     *   The page for which to form the fields.
     */
    public function fill(EditOrganizationalUnitPage $page)
    {
        $page->unitName->clear();
        $page->unitName->fill($this->unitName);
        $page->body->setBodyText($this->body);
        $page->seoDescription->fill($this->seoDescription);
        $page->headOfUnit->fill($this->headOfUnit);
        // No need to fill in locationCountry as it has BE by default already.
        // If we would set it explicitly, we would have to wait for the AJAX to
        // complete before filling in the other location fields.
        $page->locationName->fill($this->locationName);
        $page->locationStreet->fill($this->locationStreet);
        $page->locationStreetNumber->fill($this->locationStreetNumber);
        $page->locationPostalCode->fill($this->locationPostalCode);
        $page->locationCity->fill($this->locationCity);
        $page->phone->fill($this->phone);
        $page->fax->fill($this->fax);
        $page->email->fill($this->email);
        $page->website->fill($this->website);
        $page->facebook->fill($this->facebook);
        $page->twitter->fill($this->twitter);
        $page->linkedin->fill($this->linkedin);
        $page->vatNumber->fill($this->vatNumber);
    }
}
