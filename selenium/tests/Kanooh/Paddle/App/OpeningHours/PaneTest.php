<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\PaneTest.
 */

namespace Kanooh\Paddle\App\OpeningHours;

use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\OpeningHours\OpeningHoursCalendar;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\OrganizationalUnitViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
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
class PaneTest extends WebDriverTestCase
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
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

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
        $this->editOrganizationalUnitPage = new EditOrganizationalUnitPage($this);
        $this->frontendNodeViewPage = new OrganizationalUnitViewPage($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->viewPage = new ViewPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OrganizationalUnit);
        $this->appService->enableApp(new OpeningHours);
    }

    /**
     * Tests the opening hours overview in the front end.
     */
    public function testOpeningHoursCalendarPaneDetailedView()
    {
        // Create an Opening Hours Set.
        $this->cleanUpService->deleteEntities('opening_hours_set');
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title);

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

        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        // Create the pane.
        $content_type = new OpeningHoursCalendarPanelsContentType($this);

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // Make sure we cant submit if we didn't choose a node.
        $modal->submit();
        $this->assertTextPresent('Please enter a valid page.');

        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();

        $modal->submit();
        $modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        // Publish the page, go to the front end, and verify that the widget is
        // also present there.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->viewPage->go($landing_page_nid);
        $calendar = new OpeningHoursCalendar($this, $pane_uuid);
        $this->assertTrue($calendar->isPresent());
    }

    /**
     * Tests the opening hours overview in the front end.
     */
    public function testOpeningHoursCalendarPaneListView()
    {
        // Create 2 Opening Hours Sets.
        $this->cleanUpService->deleteEntities('opening_hours_set');
        $title_set_1 = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title_set_1);
        $title_set_2 = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title_set_2);

        // Create 2 Organizational Units and link them to the Opening Hours Sets.
        $title_ou_1 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_1 = $this->contentCreationService->createOrganizationalUnit($title_ou_1);
        $this->editOrganizationalUnitPage->go($nid_1);
        $this->editOrganizationalUnitPage->openingHours->fill($title_set_1);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title_set_1);

        // Save and go to the Front-end view.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $title_ou_2 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_2 = $this->contentCreationService->createOrganizationalUnit($title_ou_2);
        $this->editOrganizationalUnitPage->go($nid_2);
        $this->editOrganizationalUnitPage->openingHours->fill($title_set_2);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title_set_2);

        // Save and go to the Front-end view.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $test_data = array(
            $title_ou_1,
            $title_ou_2,
        );

        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        // Create the pane.
        $content_type_not_valid = new OpeningHoursCalendarPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type_not_valid);
        $content_type_not_valid->getForm()->viewModeRadios->list->select();
        // Make sure we cant submit if we didn't choose a node.
        $modal->submit();
        $this->assertTextPresent('Please enter a valid page.');
        $modal->close();

        $content_type = new OpeningHoursCalendarPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);
        $content_type->getForm()->viewModeRadios->list->select();
        $content_type->addNode();

        for ($i = 0; $i < count($content_type->getForm()->openingHoursListNode); $i++) {
            $content_type->getForm()->openingHoursListNode[$i]->node->fill($test_data[$i]);

            // Pick the suggestion.
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $autocomplete->pickSuggestionByPosition();
        }
        // Remove the a node.
        $content_type->getForm()->removeNode($content_type->getForm()->openingHoursListNode[1]);

        $modal->submit();
        $modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->viewPage->go($landing_page_nid);
        $calendar = new OpeningHoursCalendar($this, $pane_uuid);
        $this->assertTrue($calendar->isPresent());
        $this->assertTextNotPresent($title_ou_1);
        $this->assertTextPresent($title_ou_2);
    }
}
