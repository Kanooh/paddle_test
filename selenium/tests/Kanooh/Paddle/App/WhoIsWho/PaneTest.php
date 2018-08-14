<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\WhoIsWho\PaneTest.
 */

namespace Kanooh\Paddle\App\WhoIsWho;

use Kanooh\Paddle\Apps\WhoIsWho;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\WhoIsWho\TeamMember;
use Kanooh\Paddle\Pages\Element\Pane\WhoIsWho\WhoIsWhoPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\WhoIsWhoPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\ConfigurationForm;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\CompanyInformationTableRow;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the WhoIsWho pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AdminViewPage
     */
    protected $adminViewPage;

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
     * @var ContactPersonEditPage
     */
    protected $contactPersonEditPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var EditOrganizationalUnitPage
     */
    protected $organizationalUnitEditPage;

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

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->contactPersonEditPage = new ContactPersonEditPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->organizationalUnitEditPage = new EditOrganizationalUnitPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new WhoIsWho);
    }

    /**
     * Tests the basic configuration and functionality of the WhoIsWho pane.
     *
     * @group panes
     * @group whoiswho
     * @group contactPerson
     * @group organizationalUnit
     */
    public function testTeamMembersPane()
    {
        // Create an image atom to test with.
        $atom = $this->assetCreationService->createImage();

        // Create an organizational unit.
        $ou_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOrganizationalUnit($ou_title);

        // Create some contact persons.
        $cp_1_first = $this->alphanumericTestDataProvider->getValidValue();
        $cp_1_last = $this->alphanumericTestDataProvider->getValidValue();
        $cp_1_name = $cp_1_first . ' ' . $cp_1_last;
        $cp_1_nid = $this->contentCreationService->createContactPerson($cp_1_first, $cp_1_last);

        $info[$cp_1_name] = array(
            'function' => $this->alphanumericTestDataProvider->getValidValue(),
            'phone' => $this->alphanumericTestDataProvider->getValidValue(),
            'mobile' => $this->alphanumericTestDataProvider->getValidValue(),
        );

        $this->contactPersonEditPage->go($cp_1_nid);
        $this->contactPersonEditPage->featuredImage->selectAtom($atom['id']);

        $this->contactPersonEditPage->form->addOrganization();
        /** @var CompanyInformationTableRow $row */
        $row = $this->contactPersonEditPage->form->companyInformationTable->rows[0];
        $row->organizationalUnit->fill($ou_title);
        $row->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $this->moveto($this->contactPersonEditPage->form->lastName->getWebdriverElement());
        $autocomplete->pickSuggestionByPosition(0);

        $row->function->fill($info[$cp_1_name]['function']);
        $row->phone->fill($info[$cp_1_name]['phone']);
        $row->mobile->fill($info[$cp_1_name]['mobile']);

        $this->contactPersonEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        $cp_2_first = $this->alphanumericTestDataProvider->getValidValue();
        $cp_2_last = $this->alphanumericTestDataProvider->getValidValue();
        $cp_2_name = $cp_2_first . ' ' . $cp_2_last;
        $cp_2_nid = $this->contentCreationService->createContactPerson($cp_2_first, $cp_2_last);

        $info[$cp_2_name] = array(
            'function' => $this->alphanumericTestDataProvider->getValidValue(),
            'phone' => $this->alphanumericTestDataProvider->getValidValue(),
            'mobile' => $this->alphanumericTestDataProvider->getValidValue(),
        );

        $this->contactPersonEditPage->go($cp_2_nid);
        $this->contactPersonEditPage->featuredImage->selectAtom($atom['id']);
        /** @var CompanyInformationTableRow $row */
        $this->contactPersonEditPage->form->addOrganization();
        $row = $this->contactPersonEditPage->form->companyInformationTable->rows[0];
        $row->organizationalUnit->fill($ou_title);
        $row->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $this->moveto($this->contactPersonEditPage->form->lastName->getWebdriverElement());
        $autocomplete->pickSuggestionByPosition(0);

        $row->function->fill($info[$cp_2_name]['function']);
        $row->phone->fill($info[$cp_2_name]['phone']);
        $row->mobile->fill($info[$cp_2_name]['mobile']);

        $this->contactPersonEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create a WhoIsWho pane and assert that the team members radio button
        // is selected by default.
        $content_type = new WhoIsWhoPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $this->assertDefaultFormConfiguration($content_type->getForm());

        $content_type->getForm()->autocompleteField->fill($ou_title);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $modal->submit();
        $modal->waitUntilClosed();
        $region->refreshPaneList();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->frontEndNodeViewPage->go($nid);
        $this->assertTextPresent($cp_1_name);
        $this->assertTextPresent($cp_2_name);
    }

    /**
     * Tests the basic configuration and functionality of the WhoIsWho pane.
     *
     * @group panes
     * @group whoiswho
     * @group contactPerson
     * @group organizationalUnit
     */
    public function testLinkToTeamMembersInPane()
    {
        // Create a Contact person and add the organizational units.
        $contact_person_nid = $this->contentCreationService->createContactPerson('Joske', 'Vermeulen');


        // Create two organizational units with different addresses and publish 'em.
        $dummy_data = array(
            '1' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'city' => $this->alphanumericTestDataProvider->getValidValue(),
            ),
            '2' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'city' => $this->alphanumericTestDataProvider->getValidValue(),
            ),
        );

        foreach ($dummy_data as $key => $fields) {
            $dummy_data[$key]['nid'] = $this->contentCreationService->createOrganizationalUnit($fields['title']);
            $this->organizationalUnitEditPage->go($dummy_data[$key]['nid']);
            // Fill in the city.
            $this->organizationalUnitEditPage->locationCity->fill($fields['city']);
            $this->organizationalUnitEditPage->contextualToolbar->buttonSave->click();
            $this->adminViewPage->checkArrival();
            // Add the Organizational unit to the contact person.
            $this->addOrganizationalUnitToContactPerson($contact_person_nid, $dummy_data[$key]['title']);
            // Publish the OU.
            $this->contentCreationService->moderateNode($dummy_data[$key]['nid'], 'published');
        }

        // Publish the Contact person.
        $this->adminViewPage->go($contact_person_nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Create a basic page which we can place the team member panes on.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_nid);

        // Create a team members pane for each Organizational Unit.
        foreach ($dummy_data as $key => $fields) {
            $region = $this->layoutPage->display->getRandomRegion();
            $panes_before = $region->getPanes();
            $content_type = new WhoIsWhoPanelsContentType($this);
            $region->buttonAddPane->click();
            $this->waitUntilTextIsPresent('Add new pane');
            $modal = new AddPaneModal($this);
            $modal->selectContentType($content_type);
            $content_type->getForm()->autocompleteField->fill($fields['title']);
            $autocomplete = new AutoComplete($this);
            $autocomplete->pickSuggestionByPosition(0);
            $modal->submit();
            $modal->waitUntilClosed();
            // We need the UUID for the front-end check.
            $region->refreshPaneList();
            $panes_after = $region->getPanes();
            $pane_new = current(array_diff_key($panes_after, $panes_before));
            $dummy_data[$key]['uuid'] = $pane_new->getUuid();
        }

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Assert that each pane links to the contact person page which displays
        // the address info which is linked to the pane.
        foreach ($dummy_data as $key => $fields) {
            // Go to the front-end view of the page.
            $this->frontEndNodeViewPage->go($basic_page_nid);
            // Find the WhoIsWhoPane.
            $frontend_pane = new WhoIsWhoPane($this, $fields['uuid']);

            // Find the Contact Person and click on it.
            $contact_person = $frontend_pane->teamMembers['Joske Vermeulen'];
            /** @var TeamMember $contact_person */
            $contact_person->click();

            // Assert that you arrived on the correct page and that all address
            // info can be found.
            $this->frontEndNodeViewPage->checkArrival();
            $this->assertTextPresent($fields['title']);
            $this->assertTextPresent($fields['city']);
        }

        $this->layoutPage->go($basic_page_nid);

        // Now test the Organization selection of the whoiswho pane.
        foreach ($dummy_data as $key => $fields) {
            // Change the view mode to Organization.
            $content_type = new WhoIsWhoPanelsContentType($this);
            $backend_pane = new WhoIsWhoPane($this, $fields['uuid']);
            $backend_pane->toolbar->buttonEdit->click();
            $backend_pane->editPaneModal->waitUntilOpened();
            $content_type->getForm()->viewMode->organization->select();
            $this->waitUntilTextIsPresent('Include all underlying entities (as links)');
            $backend_pane->editPaneModal->submit();
            $backend_pane->editPaneModal->waitUntilClosed();
        }

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Assert that each pane links to the contact person page which displays
        // the address info which is linked to the pane.
        foreach ($dummy_data as $key => $fields) {
            // Go to the front-end view of the page.
            $this->frontEndNodeViewPage->go($basic_page_nid);
            // Find the WhoIsWhoPane.
            $frontend_pane = new WhoIsWhoPane($this, $fields['uuid']);
            // Find the Contact Person and click on it.
            $pane_xpath = $frontend_pane->getXPathSelectorByUuid();
            $title = $this->byXPath($pane_xpath . '//div[contains(@class, "ou-team-member")]/h3/a');
            $title->click();
            // Assert that you arrived on the correct page and that all address
            // info can be found.
            $this->frontEndNodeViewPage->checkArrival();
            $this->assertTextPresent($fields['title']);
            $this->assertTextPresent($fields['city']);
        }
    }

    /**
     * Tests the organization selection of the WhoIsWho pane.
     *
     * @group panes
     * @group whoiswho
     * @group contactPerson
     * @group organizationalUnit
     */
    public function testOrganizationPane()
    {
        // Create the master organizational unit and publish it.
        $master_title = $this->alphanumericTestDataProvider->getValidValue();
        $master_nid = $this->contentCreationService->createOrganizationalUnit($master_title);
        $this->contentCreationService->moderateNode($master_nid, workbench_moderation_state_published());

        // Create the 2 child organizational units and publish them.
        $child_1_title = $this->alphanumericTestDataProvider->getValidValue();
        $child_1_nid = $this->contentCreationService->createOrganizationalUnit($child_1_title);
        $this->setParentOrganizationalUnit($child_1_nid, $master_title);

        $child_2_title = $this->alphanumericTestDataProvider->getValidValue();
        $child_2_nid = $this->contentCreationService->createOrganizationalUnit($child_2_title);
        $this->setParentOrganizationalUnit($child_2_nid, $master_title);
        $this->contentCreationService->moderateNode($child_2_nid, workbench_moderation_state_published());

        // Create some contact persons and couple them to the organizational units.
        $cp_1_first = $this->alphanumericTestDataProvider->getValidValue();
        $cp_1_last = $this->alphanumericTestDataProvider->getValidValue();
        $cp_1_name = $cp_1_first . ' ' . $cp_1_last;
        $cp_1_nid = $this->contentCreationService->createContactPerson($cp_1_first, $cp_1_last);
        $this->addOrganizationalUnitToContactPerson($cp_1_nid, $master_title);

        $cp_2_first = $this->alphanumericTestDataProvider->getValidValue();
        $cp_2_last = $this->alphanumericTestDataProvider->getValidValue();
        $cp_2_name = $cp_2_first . ' ' . $cp_2_last;
        $cp_2_nid = $this->contentCreationService->createContactPerson($cp_2_first, $cp_2_last);
        $this->addOrganizationalUnitToContactPerson($cp_2_nid, $master_title);
        $this->contentCreationService->moderateNode($cp_2_nid, workbench_moderation_state_published());

        // Set a head of unit on the master OU.
        $this->organizationalUnitEditPage->go($master_nid);
        $this->organizationalUnitEditPage->headOfUnitAutoComplete->fill($cp_2_name);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $autocomplete->pickSuggestionByValue($suggestions[0]);
        $this->organizationalUnitEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create an WhoIsWho pane and assert that the team members radio button is selected by default.
        $content_type = new WhoIsWhoPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $content_type->getForm()->viewMode->organization->select();
        $this->waitUntilTextIsPresent('All team members');
        $content_type->getForm()->autocompleteField->fill($master_title);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $modal->submit();
        $modal->waitUntilClosed();
        $region->refreshPaneList();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        $this->frontEndNodeViewPage->go($nid);
        $this->assertTextPresent($cp_1_name);
        $this->assertTextPresent($cp_2_name);
        $this->assertTextPresent('(responsible person)');
        $this->assertTextPresent($child_1_title);
        $this->assertTextPresent($child_2_title);

        $this->userSessionService->logout();
        $this->frontEndNodeViewPage->go($nid);

        // Checking as anonymous user, if the published content is shown and the
        // unpublished content is hidden.
        $this->assertTextNotPresent($cp_1_name);
        $this->assertTextPresent($cp_2_name);
        $this->assertTextNotPresent($child_1_title);
        $this->assertTextPresent($child_2_title);
    }

    /**
     * Checks the default WhoIsWho pane configuration.
     *
     * @param ConfigurationForm $form
     *   The pane configuration form.
     */
    public function assertDefaultFormConfiguration($form)
    {
        // Assert that the 'Search Field' radio button has been selected.
        $this->assertTrue($form->viewMode->team_members->isSelected());
    }

    /**
     * Sets the parent organizational unit for another organizational unit.
     *
     * @param int $child_id
     *   The node id of the child.
     * @param int $ou_parent_title
     *   The title of the parent.
     */
    protected function setParentOrganizationalUnit($child_id, $ou_parent_title)
    {
        $this->organizationalUnitEditPage->go($child_id);
        $this->organizationalUnitEditPage->parentEntity->fill($ou_parent_title);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $autocomplete->pickSuggestionByValue($suggestions[0]);
        $this->organizationalUnitEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Adds an organizational unit to a contact persons company info.
     *
     * @param int $cp_nid
     *   The node id of the contact person.
     * @param string $ou_title
     *   The title of the organizational unit to autocomplete on.
     */
    protected function addOrganizationalUnitToContactPerson($cp_nid, $ou_title)
    {
        $this->contactPersonEditPage->go($cp_nid);
        /** @var CompanyInformationTableRow $row */
        $this->contactPersonEditPage->form->addOrganization(1);
        $row = end($this->contactPersonEditPage->form->companyInformationTable->rows);
        $row->organizationalUnit->fill($ou_title);
        $row->organizationalUnit->waitForAutoCompleteResults();
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition(0, true);
        $this->contactPersonEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }
}
