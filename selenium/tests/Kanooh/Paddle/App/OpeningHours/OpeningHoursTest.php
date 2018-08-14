<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\OpeningHoursTest.
 */

namespace Kanooh\Paddle\App\OpeningHours;

use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\ExceptionalOpeningHoursTableRow;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourAddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourEditPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\OpeningHours\ClosingDay;
use Kanooh\Paddle\Pages\Node\ViewPage\OrganizationalUnitViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\Product\ProductEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Product\ProductViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class OpeningHoursTest
 * @package Kanooh\Paddle\App\OrganizationalUnit
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class OpeningHoursTest extends WebDriverTestCase
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
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditOrganizationalUnitPage
     */
    protected $editOrganizationalUnitPage;

    /**
     * @var OrganizationalUnitViewPage
     */
    protected $frontendNodeViewPage;

    /**
     * @var OpeningHourAddPage
     */
    protected $openingHourAddPage;

    /**
     * @var OpeningHourEditPage
     */
    protected $openingHourEditPage;

    /**
     * @var ProductEditPage
     */
    protected $editProductPage;

    /**
     * @var ProductViewPage
     */
    protected $viewProductPage;

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
        $this->cleanUpService = new CleanUpService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->editOrganizationalUnitPage = new EditOrganizationalUnitPage($this);
        $this->frontendNodeViewPage = new OrganizationalUnitViewPage($this);
        $this->openingHourAddPage = new OpeningHourAddPage($this);
        $this->openingHourEditPage = new OpeningHourEditPage($this);
        $this->editProductPage = new ProductEditPage($this);
        $this->viewProductPage = new ProductViewPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * Tests the opening hours reference field.
     *
     * @group authcache
     */
    public function testOpeningHoursReferenceField()
    {
        $nid = $this->contentCreationService->createOrganizationalUnit();

        if (!module_exists('opening_hours_sets')) {
            // Go to the edit page and verify there is no opening hours field.
            $this->editOrganizationalUnitPage->go($nid);

            try {
                $this->byName('field_paddle_opening_hours[und][0][target_id]');
                $this->fail('The opening hours reference field should not be shown.');
            } catch (\Exception $e) {
                // Do nothing.
            }
        }

        // Create an opening hours set.
        $this->appService->enableApp(new OpeningHours);
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title);

        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->editOrganizationalUnitPage->go($nid);
        $this->assertContains($title, $this->editOrganizationalUnitPage->openingHours->getContent());
    }

    /**
     * Tests if the current day is displayed as first day in the opening hours
     * set.
     *
     * @group authcache
     */
    public function testCurrentDayFirst()
    {
        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->appService->enableApp(new OpeningHours);

        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title);

        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontendNodeViewPage->go($nid);
        $element = $this->byCssSelector('.title-box');
        $day = $element->text();

        $this->assertEquals(date('D'), $day);
    }

    /**
     * Tests the front end.
     *
     * @group authcache
     */
    public function testFrontEnd()
    {
        $this->appService->enableApp(new OpeningHours);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->openingHourAddPage->checkArrival();

        $tomorrow = strtotime('tomorrow');
        $closing_day_date = date('d/m/Y', $tomorrow);
        $closing_day_description = $this->alphanumericTestDataProvider->getValidValue();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->title->fill($title);
        $this->openingHourAddPage->form->closingDaysDateStart1->fill($closing_day_date);
        $this->openingHourAddPage->form->closingDaysDescription1->fill($closing_day_description);

        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');

        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendNodeViewPage->go($nid);

        $this->assertTextPresent('Exceptional closing days');
        $this->assertTextNotPresent('Exceptional opening hours');
        $this->assertTextNotPresent('Standard opening hours');

        $this->frontendNodeViewPage->exceptionalClosingDays->header->click();
        $closing_days = $this->frontendNodeViewPage->exceptionalClosingDays->closingDays;
        /** @var ClosingDay $closing_day */
        $closing_day = reset($closing_days);
        $this->assertEquals($closing_day_date, $closing_day->date->text());
        $this->assertEquals($closing_day_description, $closing_day->description->text());
        $this->assertTextPresent(date('j F', $tomorrow) . ' closed');

        $two_weeks = strtotime('+15 days');
        $closing_day_date = date('d/m/Y', $two_weeks);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());
        $this->openingHourEditPage->form->closingDaysDateStart1->clear();
        $this->openingHourEditPage->form->closingDaysDateStart1->fill($closing_day_date);

        $this->openingHourEditPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');

        $this->frontendNodeViewPage->go($nid);

        $this->assertTextNotPresent(date('j F', $two_weeks) . ' closed');
        $this->assertTextPresent('Exceptional closing days');

        // When the closing day is in the past it should not appear on the front-end.
        $yesterday = strtotime('-1 day');
        $closing_day_date = date('d/m/Y', $yesterday);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());
        $this->openingHourEditPage->form->closingDaysDateStart1->clear();
        $this->openingHourEditPage->form->closingDaysDateStart1->fill($closing_day_date);

        $this->openingHourEditPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');

        $this->frontendNodeViewPage->go($nid);
        $this->assertTextNotPresent(date('j F', $yesterday) . ' closed');
        $this->assertTextNotPresent('Exceptional closing days');

        // Make sure exceptional closing days include the begin and last day of the set.
        // Today is the last day of the set.
        $yesterday = strtotime('-1 day');
        $today = strtotime('now');
        $closing_from = date('d/m/Y', $yesterday);
        $closing_till = date('d/m/Y', $today);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());
        $this->openingHourEditPage->form->showEndDate->check();
        $this->openingHourEditPage->form->closingDaysDateStart1->clear();
        $this->openingHourEditPage->form->closingDaysDateStart1->fill($closing_from);
        $this->openingHourEditPage->form->closingDaysDateEnd1->fill($closing_till);

        $this->openingHourEditPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');

        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent('Now closed');
        $this->assertTextPresent('Exceptional closing days');

        // Today is the first day of the set.
        $tomorrow = strtotime('+1 day');
        $today = strtotime('now');
        $closing_till = date('d/m/Y', $tomorrow);
        $closing_from = date('d/m/Y', $today);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());
        $this->openingHourEditPage->form->showEndDate->check();
        $this->openingHourEditPage->form->closingDaysDateStart1->clear();
        $this->openingHourEditPage->form->closingDaysDateEnd1->clear();
        $this->openingHourEditPage->form->closingDaysDateStart1->fill($closing_from);
        $this->openingHourEditPage->form->closingDaysDateEnd1->fill($closing_till);

        $this->openingHourEditPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');

        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent('Now closed');
        $this->assertTextPresent('Exceptional closing days');
    }

    /**
     * Tests the front end label.
     *
     * @group authcache
     */
    public function testLabel()
    {
        // Create an Opening Hours Set.
        $this->appService->enableApp(new OpeningHours);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->openingHourAddPage->checkArrival();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->title->fill($title);

        $weekdays = $this->getWeekdays();

        // Make sure the Organizational Unit will be open.
        foreach ($weekdays as $day) {
            $start_time_selector = $day . "StartTime";
            $end_time_selector = $day . "EndTime";
            $this->openingHourAddPage->form->$start_time_selector->fill("0:00");
            $this->openingHourAddPage->form->$end_time_selector->fill("23:59");
        }

        // Add a description for today.
        $today = date('l');
        $today_name = strtolower($today);
        $day_description = $today_name . 'Description';
        $description = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->$day_description->fill($description);

        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create the Organizational Unit and link it to the Opening Hours Set.
        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        // Save and go to the Front-end view.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendNodeViewPage->go($nid);

        // Assert that the OU is open.
        $this->assertTextPresent('Now open');
        $this->assertTextPresent('0:00-23:59');
        $this->assertTextPresent($description);

        // Set a Closing day to today.
        $today = strtotime('now');
        $closing_day_date = date('d/m/Y', $today);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());

        $this->openingHourAddPage->form->closingDaysDateStart1->fill($closing_day_date);
        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // go to the Front-end view.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent('Now closed');
        $this->assertTextNotPresent($description);
    }

    /**
     * Tests the opening hours overview in the front end.
     *
     * @group authcache
     */
    public function testOpeningHoursOverview()
    {
        // Create an Opening Hours Set.
        $this->appService->enableApp(new OpeningHours);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->openingHourAddPage->checkArrival();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->title->fill($title);

        $weekdays = $this->getWeekdays();

        // Make sure the Organizational Unit will be open.
        foreach ($weekdays as $day) {
            $start_time_selector = $day . "StartTime";
            $end_time_selector = $day . "EndTime";
            $this->openingHourAddPage->form->$start_time_selector->fill('09:00');
            $this->openingHourAddPage->form->$end_time_selector->fill('17:00');
        }

        // Now add an exceptional opening hour for today.
        $row_description = $this->alphanumericTestDataProvider->getValidValue();
        $day_description = $this->alphanumericTestDataProvider->getValidValue();
        $rows = $this->openingHourAddPage->form->exceptionalOpeningHoursTable->rows;

        /** @var ExceptionalOpeningHoursTableRow $row */
        $row = reset($rows);
        $row->description->fill($row_description);
        $row->startDate->fill(date('d/m/Y'));
        $row->endDate->fill(date('d/m/Y'));
        $row->daysFieldset->header->click();

        $day = $row->daysFieldset->getDayByWeekdayIndex(date('w'));
        $day->startTime->fill('08:00');
        $day->endTime->fill('10:00');
        $day->description->fill($day_description);

        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create the Organizational Unit and link it to the Opening Hours Set.
        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        // Save and go to the Front-end view.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendNodeViewPage->go($nid);

        // @todo Refactor this in proper classes!
        $base_xpath = '//div[contains(@class, "opening-hours-set-calendar")]/div[contains(@class, "ohs-upcoming-day")]';
        $this->assertNotEmpty($this->byXPath("$base_xpath//div[contains(@class, \"oh-time\") and contains(text(), \"08:00-10:00\")]"));
        $this->assertNotEmpty($this->byXPath("$base_xpath//div[contains(@class, \"oh-description\") and contains(text(), \"$day_description\")]"));

        // Assert that the warning is present for the exceptional opening hours set.
        $this->assertTextPresent(t('Different opening hours apply on'));

        // Edit the set and add a closing day on today.
        $entity = reset(entity_load('opening_hours_set'));
        $now = strtotime('now');
        $closing_day_date = date('d/m/Y', $now);
        $closing_day_description = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourEditPage->go($entity->identifier());
        $this->openingHourEditPage->form->closingDaysDateStart1->fill($closing_day_date);
        $this->openingHourEditPage->form->closingDaysDescription1->fill($closing_day_description);
        // Add a second closing day in the past.
        $this->openingHourEditPage->form->closingDaysAddButton->click();
        $this->waitUntilElementIsPresent('//input[contains(@name, "field_ous_closing_days[und][1][field_ous_closing_days_date][und][0][value][date]")]');
        $past_date = date('d/m/Y', strtotime('-1 day'));
        $past_day_description = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourEditPage->form->closingDaysDateStart2->fill($past_date);
        $this->openingHourEditPage->form->closingDaysDescription2->fill($past_day_description);
        // Save the set.
        $this->openingHourEditPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        $this->frontendNodeViewPage->go($nid);
        // @todo Refactor this in proper classes!
        $base_xpath = '//div[contains(@class, "opening-hours-set-calendar")]/div[contains(@class, "ohs-upcoming-day")]';
        $closed = t('Closed');
        $this->assertNotEmpty($this->byXPath("$base_xpath//div[contains(@class, \"oh-time\") and contains(text(), \"$closed\")]"));
        $this->assertNotEmpty($this->byXPath("$base_xpath//div[contains(@class, \"oh-description\") and contains(text(), \"$closing_day_description\")]"));

        // Assert that the closing day today is shown as warning but the one in the past is not.
        $this->assertTextPresent(date('j F') . ' ' . t('closed'));
        $this->assertTextNotPresent(date('j F', strtotime('-1 day')) . ' ' . t('closed'));
    }

    /**
     * Tests the opening hours display on the Product page.
     *
     * @group Product
     * @group authcache
     */
    public function testDisplayOnProductPage()
    {
        // Enable the required paddlets.
        $this->appService->enableApp(new OpeningHours);
        $this->appService->enableApp(new Product);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        // Create an Opening Hours Set.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->openingHourAddPage->checkArrival();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->title->fill($title);

        $weekdays = $this->getWeekdays();

        // Make sure the Organizational Unit will be open.
        foreach ($weekdays as $day) {
            $start_time_selector = $day . "StartTime";
            $end_time_selector = $day . "EndTime";
            $this->openingHourAddPage->form->$start_time_selector->fill("0:00");
            $this->openingHourAddPage->form->$end_time_selector->fill("23:59");
        }

        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create the Organizational Unit and link it to the Opening Hours Set.
        $ou_title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createOrganizationalUnit($ou_title);
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title);

        // Save the OU.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a product and fill the responsible organization field.
        $product_nid = $this->contentCreationService->createProductPageViaUI();
        $this->editProductPage->go($product_nid);
        $this->editProductPage->productEditForm->organizationalUnit->fill($ou_title);
        $this->editProductPage->productEditForm->organizationalUnit->waitForAutoCompleteResults();

        // Moving Explicitly to the previous element because the admin bar
        // hovers over the element.
        // @TODO: Once the autocomplete scrolls below the admin bar
        // this should be removed.
        $this->moveto($this->editProductPage->productEditForm->form->getWebdriverElement());

        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $this->editProductPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the current opening hour is shown in the front end.
        $this->viewProductPage->go($product_nid);
        $this->assertTextPresent('Now open');
        $this->assertTextPresent('0:00-23:59');

        // Set a Closing day to today.
        $today = strtotime('now');
        $closing_day_date = date('d/m/Y', $today);
        $entity = reset(entity_load('opening_hours_set'));
        $this->openingHourEditPage->go($entity->identifier());

        $this->openingHourAddPage->form->closingDaysDateStart1->fill($closing_day_date);
        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Verify that the closed text is shown in the front end.
        $this->viewProductPage->go($product_nid);
        $this->assertTextPresent('Now closed');
    }

    /**
     * Returns an array consisting of the weekdays in string format.
     *
     * @return array
     *   An array containing all weekdays.
     */
    protected function getWeekdays()
    {
        return array(
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        );
    }
}
