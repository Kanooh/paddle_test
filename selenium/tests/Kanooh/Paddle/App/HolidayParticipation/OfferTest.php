<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\OfferTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\HolidayParticipationEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\PlacesTableRow;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationDayTripsViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationOrganisedHolidaysViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationHolidayAccommodationsViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationGroupAccommodationsViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationOverViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationMapsViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class OfferTest
 * @package Kanooh\Paddle\App\HolidayParticipation
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class OfferTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var HolidayParticipationEditPage
     */
    protected $editPage;

    /**
     * @var HolidayParticipationDayTripsViewPage
     */
    protected $hpDayTripsViewPage;

    /**
     * @var HolidayParticipationOrganisedHolidaysViewPage
     */
    protected $hpOrganisedHolidaysViewPage;

    /**
     * @var HolidayParticipationHolidayAccommodationsViewPage
     */
    protected $hpHolidayAccommodationsViewPage;

    /**
     * @var HolidayParticipationGroupAccommodationsViewPage
     */
    protected $hpGroupAccommodationsViewPage;

    /**
     * @var HolidayParticipationMapsViewPage
     */
    protected $hpMapsViewPage;

    /**
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->editPage = new HolidayParticipationEditPage($this);
        $this->frontendNodeViewPage = new FrontEndView($this);
        $this->hpDayTripsViewPage = new HolidayParticipationDayTripsViewPage($this);
        $this->hpOrganisedHolidaysViewPage = new HolidayParticipationOrganisedHolidaysViewPage($this);
        $this->hpHolidayAccommodationsViewPage = new HolidayParticipationHolidayAccommodationsViewPage($this);
        $this->hpGroupAccommodationsViewPage = new HolidayParticipationGroupAccommodationsViewPage($this);
        $this->hpMapsViewPage = new HolidayParticipationMapsViewPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new HolidayParticipation);
    }

    /**
     * Tests the node edit functionality of the offer content type.
     *
     * @group HolidayParticipation
     */
    public function testOfferNodeEdit()
    {
        // Create an Offer for later reference.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $offer_nid = $this->contentCreationService->createOfferPage($title);

        // Create an image to use as featured image
        $atom = $this->assetCreationService->createImage();

        // Go to the edit page and fill out all custom fields.
        $this->editPage->go($offer_nid);

        $body = $this->alphanumericTestDataProvider->getValidValue();
        $facebook_url = 'http://facebook.com';
        $twitter_url = 'http://twitter.com';
        $category = 'holiday accommodations';

        $this->editPage->holidayParticipationEditForm->facebook->fill($facebook_url);
        $this->editPage->holidayParticipationEditForm->twitter->fill($twitter_url);
        $this->editPage->holidayParticipationEditForm->body->setBodyText($body);
        $this->editPage->holidayParticipationEditForm->category->selectOptionByValue($category);
        $this->editPage->featuredImage->selectAtom($atom['id']);

        // Test the places field collection.
        $data = array(
            'targetGroup' => array('adults' => 'Adults'),
            'birthYearMin' => '1980',
            'birthYearMax' => '2000',
            'reservationState' => array('basedOnAvailability' => -1),
            'capacitySvp' => $this->alphanumericTestDataProvider->getValidValue(),
            'theme' => $this->alphanumericTestDataProvider->getValidValue(),
            'location' => $this->alphanumericTestDataProvider->getValidValue(),
            'periodDateStart' => '16/02/2017',
            'periodDateEnd' => '25/02/2017',
            'transportOffered' => 'uncheck',
            'internal' => 'uncheck',
        );

        $rows = $this->editPage->holidayParticipationEditForm->placesTable->rows;
        /** @var PlacesTableRow $row */
        $row = end($rows);
        foreach ($data as $field => $value) {
            if (in_array($field, array('targetGroup', 'reservationState'))) {
                $key = key($value);
                $row->{$field}->{$key}->select();
            } elseif (in_array($field, array('transportOffered', 'internal'))) {
                $row->{$field}->{$value}();
            } else {
                $row->{$field}->fill($value);
            }
        }

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go back to the edit page and verify that everything has been kept.
        $this->editPage->go($offer_nid);

        $this->assertEquals($facebook_url, $this->editPage->holidayParticipationEditForm->facebook->getContent());
        $this->assertEquals($twitter_url, $this->editPage->holidayParticipationEditForm->twitter->getContent());
        $this->assertContains($body, $this->editPage->holidayParticipationEditForm->body->getBodyText());
        $this->assertEquals($category, $this->editPage->holidayParticipationEditForm->category->getSelectedValue());

        $rows = $this->editPage->holidayParticipationEditForm->placesTable->rows;
        /** @var PlacesTableRow $row */
        $row = end($rows);
        foreach ($data as $field => $value) {
            if (in_array($field, array('targetGroup', 'reservationState'))) {
                $value = reset($value);
                $this->assertEquals($value, $row->{$field}->getSelected()->getValue());
            } elseif (in_array($field, array('transportOffered', 'internal'))) {
                if ($value == 'uncheck') {
                    $this->assertFalse($row->{$field}->isChecked());
                } else {
                    $this->assertTrue($row->{$field}->isChecked());
                }
            } else {
                $this->assertEquals($value, $row->{$field}->getContent());
            }
        }
    }

    /**
     * Tests the filtering of the offer views.
     *
     * @group HolidayParticipation
     */
    public function testOfferViewFilters()
    {
        // Delete all offer nodes, to avoid problems with pager.
        $cleanUpService = new CleanUpService($this);
        $cleanUpService->deleteEntities('node', 'offer');

        // Create some nodes for each category.
        $this->createTestData();

        // Go check if content is being filtered per category.
        $this->checkFilters($this->hpDayTripsViewPage, 3, array(
            'title' => 'Day trips node',
            'contract_type' => array(
                'hp_evenement' => 1,
            ),
            'region' => array(
                '2170' => 1,
            ),
            'year' => array(
                '2016' => 2,
                '2017' => 1,
            ),
            'month' => array(
                'January' => 1,
                'February' => 0,
            ),
        ));

        $this->checkFilters($this->hpOrganisedHolidaysViewPage, 2, array(
            'title' => 'Organised holidays node',
            'formula' => array(
                'internal' => 1,
                'external' => 1,
            ),
            'region' => array(
                '2170' => 1,
            ),
            'year' => array(
                '2016' => 1,
                '2017' => 0,
            ),
            'month' => array(
                'March' => 1,
                'April' => 0,
            ),
        ));

        $this->checkFilters($this->hpGroupAccommodationsViewPage, 3, array(
            'title' => 'Group accommodations node',
            'province' => array(
                'antwerpen' => 1,
                'limburg' => 0,
                'brussel' => 1,
                'namen' => 1,
            ),
            'room_and_board' => array(
                'hp_half_board' => 1,
                'hp_full_board' => 2,
                'hp_self_cooking' => 0,
            ),
            'capacity_range' => 1,
            'region' => array(
                '2170' => 1,
            ),
            'labels' => array(
                'hp_green_key_label' => 1,
                'hp_accessibility_label_a' => 1,
                'hp_accessibility_label_a_plus' => 0,
                'hp_cycling_label' => 0,
            ),
            'facilities' => array(
                'hp_swimming' => 1,
                'hp_pets_allowed' => 1,
            ),
            'year' => array(
                '2016' => 0,
                '2017' => 1,
            ),
            'month' => array(
                'May' => 1,
                'June' => 0,
            ),
        ));

        $this->checkFilters($this->hpHolidayAccommodationsViewPage, 4, array(
            'title' => 'Holiday accommodations node',
            'province' => array(
                'antwerpen' => 1,
                'west-vlaanderen' => 1,
                'oost-vlaanderen' => 1,
                'vlaams-brabant' => 0,
                'henegouwen' => 1,
            ),
            'room_and_board' => array(
                'hp_half_board' => 0,
                'hp_full_board' => 1,
                'hp_self_cooking' => 2,
                'hp_bed_and_breakfast' => 1,
            ),
            'region' => array(
                '2170' => 1,
            ),
            'labels' => array(
                'hp_green_key_label' => 1,
                'hp_accessibility_label_a' => 1,
                'hp_accessibility_label_a_plus' => 0,
                'hp_cycling_label' => 0,
            ),
            'facilities' => array(
                'hp_swimming' => 1,
                'hp_pets_allowed' => 1,
            ),
            'year' => array(
                '2016' => 1,
                '2017' => 1,
            ),
            'month' => array(
                'December' => 1,
                'November' => 0,
            ),
        ));

        // Make sure that the pager on day trips view works well.
        // We will only check the pager on this view for now because it had a problem.
        //see https://one-agency.atlassian.net/browse/PADVAK-88
        $this->checkPager($this->hpDayTripsViewPage, 3);
    }

    /**
     * Tests the views pager functionality.
     *
     * @param HolidayParticipationOverViewPage $viewPage
     *   The view page.
     * @param integer $count
     *   The number of nodes supposed to be on current view.
     */
    protected function checkPager($viewPage, $count)
    {
        // Create another 10 nodes.
        $atom = $this->assetCreationService->createImage();
        $this->createOffer(array(
            'title' => $this->alphanumericTestDataProvider->getValidValue(),
            'province' => 'antwerpen',
            'contract_type' => 'hp_promo',
            'category' => 'day trips',
            'featured_image' => $atom['id'],
        ), 10);
        $viewPage->go();

        // First page has always 10 nodes.
        $this->assertEquals(10, count($viewPage->offers));
        $viewPage->pager->linkPageTwo->click();
        $viewPage->checkArrival();

        // The rest is on the second page.
        $this->assertEquals($count, count($viewPage->offers));
    }

    /**
     * Method to create detailed content from the offer content type.
     *
     * @param  integer $amount
     *   The number of nodes to create.
     * @param  array $data
     *   Array holding the node info.
     */
    protected function createOffer($data, $amount = 1)
    {
        for ($i = 0; $i < $amount; $i++) {
            if ($data['title']) {
                $offer_nid = $this->contentCreationService->createOfferPage($data['title']);
            } else {
                $title = $this->alphanumericTestDataProvider->getValidValue();
                $offer_nid = $this->contentCreationService->createOfferPage($title);
            }

            $this->editPage->go($offer_nid);

            $body = $this->alphanumericTestDataProvider->getValidValue();
            $facebook_url = 'http://facebook.com';
            $twitter_url = 'http://twitter.com';

            $this->editPage->holidayParticipationEditForm->facebook->fill($facebook_url);
            $this->editPage->holidayParticipationEditForm->twitter->fill($twitter_url);
            $this->editPage->holidayParticipationEditForm->body->setBodyText($body);
            $this->editPage->holidayParticipationEditForm->category->selectOptionByValue($data['category']);
            $this->editPage->holidayParticipationEditForm->province->selectOptionByValue($data['province']);
            $this->editPage->holidayParticipationEditForm->contractType->selectOptionByValue($data['contract_type']);
            $this->editPage->featuredImage->selectAtom($data['featured_image']);

            if (isset($data['room_and_board'])) {
                $this->editPage->holidayParticipationEditForm->roomBoard->getByValue($data['room_and_board'])->check();
            }

            if (!empty($data['formula'])) {
                $this->editPage->holidayParticipationEditForm->formula->check();
            }

            if (isset($data['address'])) {
                $this->editPage->holidayParticipationEditForm->city->fill($data['address']['city']);
                $this->editPage->holidayParticipationEditForm->zipcode->fill($data['address']['zipcode']);
                $this->editPage->holidayParticipationEditForm->street->fill($data['address']['street']);
            }

            if (!empty($data['min_capacity']) && !empty($data['max_capacity'])) {
                $this->editPage->holidayParticipationEditForm->min_capacity->fill($data['min_capacity']);
                $this->editPage->holidayParticipationEditForm->max_capacity->fill($data['max_capacity']);
            }

            if (!empty($data['labels'])) {
                $this->editPage->holidayParticipationEditForm->labels->getByValue($data['labels'])->check();
            }

            if (!empty($data['facilities'])) {
                $this->editPage->holidayParticipationEditForm->facilities->getByValue($data['facilities'])->check();
            }

            if (!empty($data['contract_start_year']) && !empty($data['contract_end_year'])) {
                $this->editPage->holidayParticipationEditForm->contractStartYear->fill($data['contract_start_year']);
                $this->editPage->holidayParticipationEditForm->contractEndYear->fill($data['contract_end_year']);
            }

            if (!empty($data['validity_period'])) {
                $this->editPage->holidayParticipationEditForm->validityPeriodStart->fill($data['validity_period']);
            }

            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        }
    }

    /**
     * Creates test data for later use.
     */
    protected function createTestData()
    {
        $atom = $this->assetCreationService->createImage();

        $this->createOffer(array(
            'title' => 'Day trips node',
            'province' => 'antwerpen',
            'contract_type' => 'hp_promo',
            'category' => 'day trips',
            'featured_image' => $atom['id'],
            'address' => array(
                'city' => 'Merksem',
                'zipcode' => '2170',
                'street' => 'Alkstraat 1',
            ),
            'contract_start_year' => 2015,
            'contract_end_year' => 2017,
            'validity_period' => '5/1/2016',
        ));

        $this->createOffer(array(
            'province' => 'namen',
            'contract_type' => 'hp_promo',
            'category' => 'day trips',
            'featured_image' => $atom['id'],
            'contract_start_year' => 2016,
            'contract_end_year' => 2016,
        ));

        $this->createOffer(array(
            'province' => 'west-vlaanderen',
            'contract_type' => 'hp_evenement',
            'category' => 'day trips',
            'featured_image' => $atom['id'],
            'contract_start_year' => 2018,
            'contract_end_year' => 2018,
        ));

        $this->createOffer(array(
            'title' => 'organised holidays node',
            'province' => 'antwerpen',
            'contract_type' => 'hp_evenement',
            'category' => 'organised holidays',
            'featured_image' => $atom['id'],
            'formula' => 0,
            'address' => array(
                'city' => 'Merksem',
                'zipcode' => '2170',
                'street' => 'Alkstraat 1',
            ),
            'contract_start_year' => 2016,
            'contract_end_year' => 2016,
            'validity_period' => '5/3/2016',
        ));

        $this->createOffer(array(
            'province' => 'west-vlaanderen',
            'contract_type' => 'hp_promo',
            'category' => 'organised holidays',
            'featured_image' => $atom['id'],
            'formula' => 1,
            'contract_start_year' => 2018,
            'contract_end_year' => 2018,
        ));

        $this->createOffer(array(
            'title' => 'group accommodations node',
            'province' => 'antwerpen',
            'contract_type' => 'hp_evenement',
            'category' => 'group accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_half_board',
            'min_capacity' => 1,
            'max_capacity' => 10,
            'address' => array(
                'city' => 'Merksem',
                'zipcode' => '2170',
                'street' => 'Alkstraat 1',
            ),
            'labels' => 'hp_green_key_label',
            'facilities' => 'hp_swimming',
            'contract_start_year' => 2017,
            'contract_end_year' => 2018,
            'validity_period' => '5/5/2016',
        ));

        $this->createOffer(array(
            'province' => 'namen',
            'contract_type' => 'hp_evenement',
            'category' => 'group accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_full_board',
            'labels' => 'hp_accessibility_label_a',
            'facilities' => 'hp_pets_allowed',
            'contract_start_year' => 2015,
            'contract_end_year' => 2015,
        ));

        $this->createOffer(array(
            'province' => 'brussel',
            'contract_type' => 'hp_promo',
            'category' => 'group accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_full_board',
            'contract_start_year' => 2018,
            'contract_end_year' => 2018,
        ));

        $this->createOffer(array(
            'title' => 'holiday accommodations node',
            'province' => 'henegouwen',
            'contract_type' => 'hp_evenement',
            'category' => 'holiday accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_self_cooking',
            'facilities' => 'hp_swimming',
            'contract_start_year' => 2012,
            'contract_end_year' => 2018,
        ));

        $this->createOffer(array(
            'province' => 'antwerpen',
            'contract_type' => 'hp_evenement',
            'category' => 'holiday accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_bed_and_breakfast',
            'address' => array(
                'city' => 'Merksem',
                'zipcode' => '2170',
                'street' => 'Alkstraat 1',
            ),
            'facilities' => 'hp_pets_allowed',
            'contract_start_year' => 2018,
            'contract_end_year' => 2018,
            'validity_period' => '5/12/2016',
        ));

        $this->createOffer(array(
            'province' => 'oost-vlaanderen',
            'contract_type' => 'hp_promo',
            'category' => 'holiday accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_self_cooking',
            'labels' => 'hp_accessibility_label_a',
        ));

        $this->createOffer(array(
            'province' => 'west-vlaanderen',
            'contract_type' => 'hp_promo',
            'category' => 'holiday accommodations',
            'featured_image' => $atom['id'],
            'room_and_board' => 'hp_full_board',
            'labels' => 'hp_green_key_label',
            'contract_start_year' => 2012,
            'contract_end_year' => 2015,
        ));
    }

    /**
     * Method to check filters on the views pages.
     *
     * @param HolidayParticipationOverViewPage $viewPage
     *   The view page.
     * @param integer $count
     *   The number of nodes supposed to be on current view.
     * @param array $data
     *   Array holding the data to filter on.
     */
    protected function checkFilters($viewPage, $count, $data)
    {
        // Check if the content is being filtered.
        $viewPage->go();
        $viewPage->checkArrival();
        $this->assertEquals($count, count($viewPage->offers));
        $viewPage->categoryLinks->checklinks(array(
            'GroupAccommodations',
            'DayTrips',
            'OrganisedHolidays',
            'HolidayAccommodations'
        ));

        if (!empty($data['province'])) {
            // Assert that we got 11 provinces and (ALL) options.
            $options = $viewPage->exposedFilters->province->getOptions();
            $this->assertEquals(12, count($options));
            // Assert that All is the default value.
            $selected = $viewPage->exposedFilters->province->getSelectedValue();
            $this->assertEquals($selected, 'All');

            // Test the province filter.
            foreach ($data['province'] as $province => $number) {
                $viewPage->exposedFilters->province->selectOptionByValue($province);
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
            }

            // Reset the province filter.
            $viewPage->exposedFilters->province->selectOptionByValue('All');
        }

        if (!empty($data['region'])) {
            foreach ($data['region'] as $zipcode => $number) {
                // Test region filter.
                $viewPage->exposedFilters->region->fill($zipcode);

                $autocomplete = new AutoComplete($this);
                $autocomplete->waitUntilSuggestionCountEquals(1);
                $autocomplete->pickSuggestionByPosition(0);

                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
                $viewPage->exposedFilters->region->clear();
            }
        }

        // Test the contract type.
        if (empty($data['contract_type'])) {
            $this->assertFalse($viewPage->exposedFilters->temporaryEvent);
        } else {
            // Test the contract type filter.
            foreach ($data['contract_type'] as $type => $number) {
                $viewPage->exposedFilters->temporaryEvent->check();
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
            }

            // Reset contract type filter.
            $viewPage->exposedFilters->temporaryEvent->uncheck();
        }

        // Test the formula.
        if (!empty($data['formula'])) {
            $viewPage->exposedFilters->formula->getByValue(1)->check();
            $viewPage->exposedFilters->search->click();
            $this->assertEquals($data['formula']['internal'], count($viewPage->offers));
            $viewPage->exposedFilters->formula->getByValue(1)->uncheck();

            $viewPage->exposedFilters->formula->getByValue(0)->check();
            $viewPage->exposedFilters->search->click();
            $this->assertEquals($data['formula']['external'], count($viewPage->offers));
            $viewPage->exposedFilters->formula->getByValue(0)->uncheck();
        }

        // Test the room and board.
        if (!empty($data['room_and_board'])) {
            foreach ($data['room_and_board'] as $key => $number) {
                $viewPage->exposedFilters->roomBoard->getByValue($key)->check();
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
                $viewPage->exposedFilters->roomBoard->getByValue($key)->uncheck();
            }
        }

        // Test capacity range filter.
        if (!empty($data['capacity_range'])) {
            $viewPage->exposedFilters->capacityRange->fill('7');
            $viewPage->exposedFilters->search->click();
            $this->assertEquals(1, count($viewPage->offers));
            $viewPage->exposedFilters->capacityRange->fill('17');
            $viewPage->exposedFilters->search->click();
            $this->assertEquals(0, count($viewPage->offers));
            $viewPage->exposedFilters->capacityRange->clear();
        }

        // Test the labels.
        if (!empty($data['labels'])) {
            foreach ($data['labels'] as $key => $number) {
                $viewPage->exposedFilters->labels->getByValue($key)->check();
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
                $viewPage->exposedFilters->labels->getByValue($key)->uncheck();
            }
        }

        // Test the facilities.
        if (!empty($data['facilities'])) {
            foreach ($data['facilities'] as $key => $number) {
                $viewPage->exposedFilters->facilities->getByValue($key)->check();
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
                $viewPage->exposedFilters->facilities->getByValue($key)->uncheck();
            }
        }

        if (!empty($data['year'])) {
            foreach ($data['year'] as $year => $number) {
                $allowed_years = array(
                    'All' => 'Which year',
                    date('Y') => date('Y'),
                    date('Y', strtotime('+1 year')) => date('Y', strtotime('+1 year')),
                );

                $this->assertEquals($allowed_years, $viewPage->exposedFilters->year->getOptions());

                if (in_array($year, $allowed_years)) {
                    $viewPage->exposedFilters->year->selectOptionByValue($year);
                    $viewPage->exposedFilters->search->click();
                    $this->assertEquals($number, count($viewPage->offers));
                }
            }

            $viewPage->exposedFilters->year->selectOptionByValue('All');
        }

        if (!empty($data['month'])) {
            foreach ($data['month'] as $month => $number) {
                $viewPage->exposedFilters->month->selectOptionByLabel($month);
                $viewPage->exposedFilters->search->click();
                $this->assertEquals($number, count($viewPage->offers));
            }

            $viewPage->exposedFilters->month->selectOptionByValue('All');
        }

        $viewPage->exposedFilters->search->click();

        // Test the filter by title.
        if (!empty($data['title'])) {
            $viewPage->exposedFilters->title->fill($data['title']);
            $viewPage->exposedFilters->search->click();
            $this->assertEquals(1, count($viewPage->offers));
        }

        // Check maps view page.
        $viewPage->switcher->linkMap->click();
        $this->hpMapsViewPage->waitUntilPageIsLoaded();
        $this->assertEquals(1, count($viewPage->mapElements));
        $this->hpMapsViewPage->switcher->linkList->click();
        $viewPage->checkArrival();
    }
}
