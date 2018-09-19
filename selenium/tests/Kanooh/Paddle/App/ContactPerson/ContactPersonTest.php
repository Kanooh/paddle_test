<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContactPersonTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateContactPersonModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ContactPersonViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Contact Person Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContactPersonTest extends WebDriverTestCase
{
    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The 'Add content' page.
     *
     * @var AddContentPage
     */
    protected $addContentPage;

    /**
     * The 'Create contact person' modal.
     *
     * @var CreateContactPersonModal
     */
    protected $createContactPersonModal;

    /**
     * The form filler for the contact person edit form.
     *
     * @var ContactPersonRandomFiller
     */
    protected $contactPersonRandomFiller;

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The contact person edit page.
     *
     * @var ContactPersonEditPage
     */
    protected $editContactPersonPage;

    /**
     * The panels display of a landing page.
     *
     * @var PanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Autocomplete suggestions.
     *
     * @var AutoComplete
     */
    protected $autoComplete;

    /**
     * The frontend view page of a landing page.
     *
     * @var LandingPageViewPage
     */
    protected $landingViewPage;

    /**
     * Test content
     *
     * @var array
     */
    protected $testContent;

    /**
     * The content region configuration page.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * The utility class for common function for content regions.
     *
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * The panels display of a contact person.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The panels display of a landing page.
     *
     * @var LandingPagePanelsContentPage
     */
    protected $landingPagePanelsPage;

    /**
     * Front end view page of the contact person.
     *
     * @var ContactPersonViewPage
     */
    protected $contactPersonViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->assetCreationService = new AssetCreationService($this);
        $this->addContentPage = new AddContentPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->autoComplete = new AutoComplete($this);
        $this->contactPersonViewPage = new ContactPersonViewPage($this);
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionUtility = new ContentRegionUtility($this);
        $this->createContactPersonModal = new CreateContactPersonModal($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->editContactPersonPage = new ContactPersonEditPage($this);
        $this->contactPersonRandomFiller = new ContactPersonRandomFiller();
        $this->landingPageLayoutPage = new PanelsContentPage($this);
        $this->landingPagePanelsPage = new LandingPagePanelsContentPage($this);
        $this->landingViewPage = new LandingPageViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->random = new Random();

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new ContactPerson);

        // Make sure OU is disabled.
        $app = new OrganizationalUnit;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->random->name(64);
        $this->testContent['all_pages']['bottom'] = $this->random->name(64);
        $this->testContent['contact_person']['right'] = $this->random->name(64);
        $this->testContent['contact_person']['bottom'] = $this->random->name(64);
    }

    /**
     * Tests the creation of a contact person.
     *
     * @group editing
     * @group contactPerson
     */
    public function testCreate()
    {
        // Create a image atom to test with.
        $atom = $this->assetCreationService->createImage();

        // Create an extra contact person to link the manager field to.
        $manager_first_name = $this->random->name(6);
        $manager_last_name = $this->random->name(6);
        $manager_nid = $this->contentCreationService->createContactPerson($manager_first_name, $manager_last_name);
        $this->administrativeNodeViewPage->go($manager_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Contact person".
        $this->addContentPage->links->linkContactPerson->click();
        $this->createContactPersonModal->waitUntilOpened();
        $this->assertTextPresent('information modal dialog');
        // Don't fill in required fields.
        $this->createContactPersonModal->submit();
        // Ensure we get 2 error messages.
        $this->waitUntilElementIsPresent('//div[contains(@class, "messages")]');
        $elements = $this->elements(
            $this->using('xpath')->value(
                $this->createContactPersonModal->getXPathSelector() .
                '//div[@class="messages error"]/ul/li'
            )
        );
        $this->assertEquals(2, count($elements));

        // Fill in required fields.
        $this->contactPersonRandomFiller->randomize();
        $this->createContactPersonModal->firstName->fill($this->contactPersonRandomFiller->firstName);
        $this->createContactPersonModal->lastName->fill($this->contactPersonRandomFiller->lastName);
        $this->createContactPersonModal->submit();
        $this->createContactPersonModal->waitUntilClosed();

        $this->administrativeNodeViewPage->checkArrival();
        // Wait until we see confirmation that the node has been created.
        $this->waitUntilElementIsPresent('//div[@id="messages"]');
        // This proves first and last name are concatenated into the title of
        // the page. The contact person's first name, a space, and the contact
        // person's last name, e.g. "Jan Janssen" or "John Doe".
        $this->assertTextPresent(
            'Contact person ' . $this->contactPersonRandomFiller->firstName .
            ' ' . $this->contactPersonRandomFiller->lastName .
            ' has been created.'
        );

        // Go to the preview, and verify none of the icons is shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->contactPersonViewPage->checkArrival();
        $this->contactPersonViewPage->assertNoIconsRendered();

        // Click the "Page properties" button in the contextual toolbar.
        $this->contactPersonViewPage->previewToolbar->closeButton->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        // Then I see the edit form of the contact person.
        $this->editContactPersonPage->checkArrival();

        // Set the photo field.
        $this->editContactPersonPage->form->photo->selectAtom($atom['id']);

        // Where the form fields of "first name" and "last name" have the values entered before.
        $required_fields = array('firstName', 'lastName');
        foreach ($required_fields as $field) {
            $this->assertEquals(
                $this->contactPersonRandomFiller->$field,
                $this->editContactPersonPage->form->$field->getContent()
            );
        }

        // I see there is no title field.
        $this->assertCount(
            0,
            $this->elements($this->using('name')->value('title'))
        );

        // Set the manager field.
        $this->editContactPersonPage->form->manager->fill($manager_first_name);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $autocomplete->pickSuggestionByValue($suggestions[0]);

        // I can fill in here organizational unit level 1, level 2 and level 3.
        // Fill in all other fields.
        $this->contactPersonRandomFiller->fill($this->editContactPersonPage);

        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the edit page and verify all fields have the correct values
        // set.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editContactPersonPage->checkArrival();
        $other_fields = array(
            'organizationalUnitLevel1',
            'organizationalUnitLevel2',
            'organizationalUnitLevel3',
            'locationTitle',
            'addressStreet',
            'addressStreetNumber',
            'addressPostalCode',
            'addressCity',
            'addressCountry',
            'email',
            'function',
            'linkedin',
            'website',
            'yammer',
            'twitter',
            'skype',
            'mobilePhone',
            'officePhone',
            'manager',
            'photo',
            'office',
        );

        foreach (array_merge($other_fields, $required_fields) as $field) {
            if ($field == 'addressCountry') {
                $this->assertEquals(
                    $this->contactPersonRandomFiller->$field,
                    $this->editContactPersonPage->form->$field->getSelectedValue()
                );
            } elseif ($field == 'manager') {
                $this->assertEquals(
                    $manager_first_name . ' ' . $manager_last_name . ' (' . $manager_nid . ')',
                    $this->editContactPersonPage->form->manager->getContent()
                );
            } elseif ($field == 'photo') {
                $atom = $this->editContactPersonPage->form->photo->atoms[0];
                $this->assertTrue($atom->removeButton->displayed());
            } else {
                $this->assertEquals(
                    $this->contactPersonRandomFiller->$field,
                    $this->editContactPersonPage->form->$field->getContent()
                );
            }
        }
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // Verify that in the front end the fields are shown.
        $this->contactPersonViewPage->checkArrival();
        $this->contactPersonViewPage->assertIconsRendered();
        $this->contactPersonViewPage->assertLayoutMarkup();

        // Verify that the "http:" is stripped from the links.
        foreach (array('linkedin', 'website', 'twitter', 'yammer') as $field) {
            $this->assertTextNotPresent($this->contactPersonRandomFiller->$field);
            $show_value = preg_replace('#^https?://#', '', $this->contactPersonRandomFiller->$field);
            $show_value = mb_strimwidth($show_value, 0, 72, '...');
            $this->assertTextPresent($show_value);
        }

        // Verify that the manager field is a link.
        $this->assertTrue($this->contactPersonViewPage->checkManagerLink($manager_first_name . ' ' . $manager_last_name, $manager_nid));
    }

    /**
     * Tests the contact person pane.
     *
     * @group contactPerson
     * @group panes
     */
    public function testPane()
    {
        // Create a contact person.
        $contact_person_nid = $this->contentCreationService->createContactPerson();
        // Fill all fields with values.
        /* @var $contact_person_values \Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller */
        $contact_person_values = $this->contentCreationService->fillContactPersonWithRandomValues($contact_person_nid);

        // Go to the node edit page and set a very long first and last name and
        // very long organizational unit levels.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editContactPersonPage->checkArrival();
        $first_name = $this->alphanumericTestDataProvider->getValidValue(127);
        $this->editContactPersonPage->form->firstName->fill($first_name);
        $last_name = $this->alphanumericTestDataProvider->getValidValue(127);
        $this->editContactPersonPage->form->lastName->fill($last_name);
        $level_1 = $this->alphanumericTestDataProvider->getValidValue(255);
        $this->editContactPersonPage->form->organizationalUnitLevel1->fill($level_1);
        $level_2 = $this->alphanumericTestDataProvider->getValidValue(255);
        $this->editContactPersonPage->form->organizationalUnitLevel2->fill($level_2);
        $level_3 = $this->alphanumericTestDataProvider->getValidValue(255);
        $this->editContactPersonPage->form->organizationalUnitLevel3->fill($level_3);
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Retrieve the values for later use.
        $title = $first_name . ' ' . $last_name;
        $function = $contact_person_values->function;
        $email = $contact_person_values->email;
        $fax = $contact_person_values->fax;
        $phone_office = $contact_person_values->officePhone;
        $mobile_office = $contact_person_values->mobilePhone;
        $location_name = $contact_person_values->locationTitle;
        $location_street = $contact_person_values->addressStreet;
        $location_postalcode = $contact_person_values->addressPostalCode;
        $location_city = $contact_person_values->addressCity;

        // Create a landing page (to put a pane on).
        $landing_nid = $this->contentCreationService->createLandingPage();

        // Add a contact person pane to the landing page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPageLayoutPage->checkArrival();
        $region = $this->landingPageLayoutPage->display->getRandomRegion();
        $contact_person_pane = new ContactPersonPanelsContentType($this);

        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->selectContentType($contact_person_pane);

        // Select the node we just created.
        // Test if the dropdown works for the node path.
        $expected_suggestion = $level_1 . ' > ' . $level_2 . ' > ' . $level_3 . ' > ' . $title . ' (node/' . $contact_person_nid . ')';

        $contact_person_pane->getForm()->contactPersonAutocompleteField->fill('node/' . $contact_person_nid);
        $autocomplete = new AutoComplete($this);
        $autocomplete->pickSuggestionByPosition(0);

        $modal->submit();
        $modal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($title);

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));

        // Verify that the correct data is shown in the pane. By default the
        // short view mode should be used.
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextPresent($function, $pane->getWebdriverElement());
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextNotPresent($phone_office, $pane->getWebdriverElement());
        $this->assertTextNotPresent($fax, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_city, $pane->getWebdriverElement());

        // Verify that the filled in values remain if you edit the pane.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        // Ensure the form element is prefilled as filled in before.
        $this->assertEquals(
            $expected_suggestion,
            $contact_person_pane->getForm()->contactPersonAutocompleteField->getContent()
        );

        // Change the view mode.
        $contact_person_pane->getForm()->viewModeMedium->select();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($phone_office);

        // Verify that new data is shown.
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextPresent($function, $pane->getWebdriverElement());
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextPresent($phone_office, $pane->getWebdriverElement());
        $this->assertTextPresent($mobile_office, $pane->getWebdriverElement());
        $this->assertTextNotPresent($fax, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_city, $pane->getWebdriverElement());

        // Change the view mode one more time.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $contact_person_pane->getForm()->viewModeLong->select();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($location_name);

        // Verify that all expected data is shown.
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextPresent($function, $pane->getWebdriverElement());
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextPresent($phone_office, $pane->getWebdriverElement());
        $this->assertTextPresent($mobile_office, $pane->getWebdriverElement());
        $this->assertTextPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextPresent($location_city, $pane->getWebdriverElement());
        $this->assertTextNotPresent($fax, $pane->getWebdriverElement());

        // Get out of the edit page so that subsequent tests are not bothered
        // by an alert.
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->administrativeNodeViewPage->go($contact_person_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();

        // Now go and remove the address info from the CP node.
        $this->editContactPersonPage->form->addressCity->clear();
        $this->editContactPersonPage->form->addressPostalCode->clear();
        $this->editContactPersonPage->form->addressStreet->clear();
        $this->editContactPersonPage->form->addressStreetNumber->clear();
        $this->editContactPersonPage->form->locationTitle->clear();
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Address should not be visible on front end if only country is filled.
        $this->administrativeNodeViewPage->go($landing_nid);
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_city, $pane->getWebdriverElement());
        $this->assertTextNotPresent('Belgium', $pane->getWebdriverElement());
    }

    /**
     * Test content region panes with contact person settings.
     *
     * @group contactPerson
     * @group panes
     */
    public function testAddContentRegionPane()
    {
        $this->contentRegionConfigurationPage->go();

        // Add custom content panes to the 'contact person' content region display.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('contact_person')->editLink,
            $this->testContent['contact_person']['right'],
            $this->testContent['contact_person']['bottom']
        );

        // Create a new landing page. Continue on to the page layout.
        $nid = $this->contentCreationService->createLandingPage();
        $this->landingPageLayoutPage->go($nid);

        // Test all combinations of content types and regions.
        foreach (array('right', 'bottom') as $region) {
            // Make sure the editor is fully loaded.
            $this->landingPageLayoutPage->waitUntilPageIsLoaded();
            $pane = $this->contentRegionUtility->addContentRegionPane(
                'contact_person',
                $region,
                $this->landingPageLayoutPage->display
            );
            // Check that the test content is visible.
            $this->assertTextPresent($this->testContent['contact_person'][$region]);
            // Remove the pane.
            $pane->delete();
        }
        // Save the page, so that the following test is not confronted with an
        // alert box.
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the content region panes on contact person pages.
     *
     * @group contactPerson
     * @group panes
     */
    public function testContentRegionsOnContactPerson()
    {
        // Go to the content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Click 'Edit content for all pages' and add two panes: one to the
        // right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->links->linkEditContentForAllPages,
            $this->testContent['all_pages']['right'],
            $this->testContent['all_pages']['bottom']
        );

        // Override the setting for the simple contact pages.
        $this->contentRegionConfigurationPage->getOverride('contact_person')->enable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('contact_person')->checkbox;
        $this->assertTrue($checkbox->selected());

        // Click 'Edit content for every contact person page' and add two panes:
        // one to the right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('contact_person')->editLink,
            $this->testContent['contact_person']['right'],
            $this->testContent['contact_person']['bottom']
        );

        // Create a contact person. We end up on the administrative node view.
        $nid = $this->contentCreationService->createContactPerson();

        // Go to the front-end view and check that the chosen panes for the
        // contact person content regions are shown.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->contactPersonViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['contact_person']['right']);
        $this->assertTextPresent($this->testContent['contact_person']['bottom']);

        // Go back to the administrative node view and click on 'Page layout'.
        // The existence of preview toolbar on a ViewPage is not guaranteed
        // as anonymous users will not see it. Because of this we instantiate
        // it locally.
        $previewToolbar = new PreviewToolbar($this);
        $previewToolbar->closeButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Add two custom content panes, one to the right region, and one to the
        // bottom region.
        $custom_pane_content = array(
            'right' => $this->random->name(64),
            'bottom' => $this->random->name(64),
        );
        $regions = $this->layoutPage->display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $custom_pane_content['right'];
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $custom_pane_content['bottom'];
        $regions['bottom']->addPane($custom_content_pane);

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');

        // Go to the frontend view and check that the new panes are shown in
        // addition to the chosen panes for the contact person content
        // regions.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->contactPersonViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['contact_person']['right']);
        $this->assertTextPresent($this->testContent['contact_person']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go back to the global content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Set the checkbox to use global content settings and click 'Save.
        $this->contentRegionConfigurationPage->getOverride('contact_person')->disable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('contact_person')->checkbox;
        $this->assertFalse($checkbox->selected());

        // Go back to the administrative node view of the contact person. Check that
        // the global panes are now shown.
        $this->administrativeNodeViewPage->go($nid);

        // Go to the front page of the contact person. Check that the global panes
        // are now shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->contactPersonViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Check that the custom panes are still shown.
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Check that the simple contact page panes are not shown.
        $this->assertTextNotPresent($this->testContent['contact_person']['right']);
        $this->assertTextNotPresent($this->testContent['contact_person']['bottom']);
    }

    /**
     * Tests that the photo field is tracked as reference.
     *
     * @group linkChecker
     */
    public function testContactPersonPhotoFieldReference()
    {
        // Create a image atom use as photo.
        $atom = $this->assetCreationService->createImage();

        // Create a Contact person node to test with.
        $nid = $this->contentCreationService->createContactPerson();

        // Add the atom as a photo.
        $this->editContactPersonPage->go($nid);
        $this->editContactPersonPage->form->photo->selectAtom($atom['id']);
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Ensure there is a reference record for this.
        $references = reference_tracker_get_inbound_references('scald_atom', $atom['id']);
        $expected_references = array('node' => array($nid));
        $this->assertEquals($expected_references, $references);
    }

    /**
     * Regression Test that checks if the Skype name is shown on the person page.
     *
     * @group contactPerson
     *
     */
    public function testContactPersonSkypeName()
    {
        $skype_name = $this->alphanumericTestDataProvider->getValidValue(15);
        // Create a Contact person node to test with, pass the Skype name with the method.
        $nid = $this->contentCreationService->createContactPerson(null, null, array("field_paddle_cp_skype" => $skype_name));

        $this->administrativeNodeViewPage->go($nid);

        // Ensure the Skype name is visible.
        $this->assertTextPresent($skype_name);
    }

    /**
     * Tests that the body pane is not rendered when only summary is filled in.
     *
     * @group contactPerson
     */
    public function testBodyPaneNotRenderedWithSummaryFilledIn()
    {
        $summary = $this->alphanumericTestDataProvider->getValidValue(60);
        $body = array(
            'summary' => $summary,
        );
        $nid = $this->contentCreationService->createContactPerson(null, null, array("body" => $body));

        $this->administrativeNodeViewPage->go($nid);

        // Ensure the Summary name is not visible.
        $this->assertTextNotPresent($summary);
        // Ensure that the pane is not rendered.
        try {
            $this->byCssSelector('.pane-node-body');
            $this->fail('Body pane should not be rendered.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }
}
