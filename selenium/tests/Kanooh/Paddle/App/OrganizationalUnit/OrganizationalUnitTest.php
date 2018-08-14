<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\OrganizationalUnitTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Element\Display\PanelsIPEDisplay;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPageRandomFiller;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\OrganizationalUnitViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Organizational Unit Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class OrganizationalUnitTest extends WebDriverTestCase
{
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
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The form filler for the organizational unit node edit form.
     *
     * @var EditOrganizationalUnitPageRandomFiller
     */
    protected $editOrganizationalUnitPageRandomFiller;

    /**
     * The front end node view page.
     *
     * @var OrganizationalUnitViewPage
     */
    protected $frontendNodeViewPage;

    /**
     * The panels display of a landing page.
     *
     * @var PanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * The organizational unit edit page.
     *
     * @var EditOrganizationalUnitPage
     */
    protected $editOrganizationalUnitPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The panels display of a landing page.
     *
     * @var LandingPagePanelsContentPage
     */
    protected $landingPagePanelsPage;

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
     * The panels display of an organizational unit.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        // Create some instances to use later on.
        $this->addContentPage = new AddContentPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionUtility = new ContentRegionUtility($this);
        $this->editOrganizationalUnitPage = new EditOrganizationalUnitPage($this);
        $this->editOrganizationalUnitPageRandomFiller = new EditOrganizationalUnitPageRandomFiller();
        $this->frontendNodeViewPage = new OrganizationalUnitViewPage($this);
        $this->landingPageLayoutPage = new PanelsContentPage($this);
        $this->landingPagePanelsPage = new LandingPagePanelsContentPage($this);
        $this->landingViewPage = new LandingPageViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->random = new Random();

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->random->name(64);
        $this->testContent['all_pages']['bottom'] = $this->random->name(64);
        $this->testContent['organizational_unit']['right'] = $this->random->name(64);
        $this->testContent['organizational_unit']['bottom'] = $this->random->name(64);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OrganizationalUnit);
        // Make sure CP is disabled to check the default behaviour.
        $app = new ContactPerson;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
        $this->appService->enableApp(new OpeningHours);
    }

    /**
     * Tests the creation of an organizational unit.
     *
     * @group editing
     */
    public function testCreate()
    {
        // Create some OU's and use them as parent entities of each other.
        $ou_parents = $this->createOrganizationalUnitParents();

        $nid = $this->createRandomOrganizationalUnit();

        // Create a image atom use as logo.
        $atom = $this->assetCreationService->createImage();

        // Retrieve the data used to fill the form to check for their presence.
        $title = $this->editOrganizationalUnitPageRandomFiller->unitName;
        $body = $this->editOrganizationalUnitPageRandomFiller->body;
        $seo_description = $this->editOrganizationalUnitPageRandomFiller->seoDescription;
        // Add the atom as a logo.
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->featuredImage->selectAtom($atom['id']);
        $this->editOrganizationalUnitPage->locationCountry->isDisplayed();
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->editOrganizationalUnitPage->go($nid);
        $this->fillOrganizationalUnitParentEntity($nid, end($ou_parents));
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        $contact_fields = array(
            'locationName',
            'locationStreet',
            'locationStreetNumber',
            'locationPostalCode',
            'locationCity',
            'locationCountry',
            'phone',
            'fax',
            'email',
            'website',
            'facebook',
            'twitter',
            'linkedin',
            'vatNumber',
        );
        $other_fields = array(
            'headOfUnit',
        );

        // Check that the title and body text are shown in the preview.
        $this->assertTextPresent($title);
        $this->assertTextPresent($body);

        // Go to the front end view.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->checkArrival();
        $this->assertTextPresent($title);
        $this->assertTextPresent($body);
        $this->frontendNodeViewPage->assertLayoutMarkup();
        $this->frontendNodeViewPage->assertIconsRendered();

        $this->userSessionService->logout();
        $this->frontendNodeViewPage->go($nid);
        foreach ($ou_parents as $key => $value) {
            $this->assertTextNotPresent($value);
        }

        $this->userSessionService->login('ChiefEditor');
        $this->frontendNodeViewPage->go($nid);
        // Make sure all parent entities are rendered as links.
        // We should have the 4 parent entities available on this page.
        foreach ($ou_parents as $key => $value) {
            $this->frontendNodeViewPage->assertParentEntities($key, $value);
        }

        // Check that the previously used values are shown in the front end.
        $this->byXPath('//h1[@id="page-title" and text()="' . $title . '"]');
        $this->assertTextPresent($body);
        foreach ($contact_fields as $field) {
            if ($field == 'website' || $field == 'linkedin' || $field == 'twitter' || $field == 'facebook') {
                $url_field = preg_replace('#^https?://#', '', $this->editOrganizationalUnitPageRandomFiller->$field);
                $this->assertTextPresent($url_field);
            } else {
                if ($field == 'locationCountry') {
                    $this->assertTextPresent('Belgium');
                } else {
                    $this->assertTextPresent($this->editOrganizationalUnitPageRandomFiller->$field);
                }
            }
        }
        $this->byXPath('//head/meta[@name="description" and @content="' . $seo_description . '"]');

        // Go back to the node edit form.
        $toolbar = new PreviewToolbar($this);
        $toolbar->closeButton()->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editOrganizationalUnitPage->checkArrival();

        // Check that the form fields are pre-filled with the previously used values.
        $this->assertEquals($title, $this->editOrganizationalUnitPage->unitName->attribute('value'));
        $this->byXPath('//textarea[@id="edit-body-und-0-value" and contains(text(), "' . $body . '")]');
        $this->byXPath('//textarea[@id="edit-field-paddle-seo-description-und-0-value" and normalize-space(text())="' . $seo_description . '"]');
        $atoms = $this->editOrganizationalUnitPage->featuredImage->atoms;
        $this->assertNotNull($atoms[0]->title);

        foreach (array_merge($contact_fields, $other_fields) as $field) {
            $this->assertEquals(
                $this->editOrganizationalUnitPageRandomFiller->$field,
                $this->editOrganizationalUnitPage->$field->attribute('value')
            );
        }

        // Check that the contact information fields are grouped.
        $field_group = $this->byCssSelector('div.pane-additional-fields div.contact-information');
        foreach ($contact_fields as $field) {
            $element_criteria = $this->using('name')->value(
                $this
                    ->editOrganizationalUnitPage
                    ->$field
                    ->attribute('name')
            );
            $this->assertCount(1, $field_group->elements($element_criteria));
        }
        // And ensure the other fields are not in that group.
        foreach ($other_fields as $field) {
            $element_criteria = $this->using('name')->value(
                $this
                    ->editOrganizationalUnitPage
                    ->$field
                    ->attribute('name')
            );
            $this->assertCount(0, $field_group->elements($element_criteria));
        }

        // Save the page to avoid an alert box messing up subsequent tests.
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
    }

    /**
     * Tests the organizational unit pane.
     *
     * @group modals
     * @group panes
     */
    public function testPane()
    {
        // Log in as editor.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');

        // Create an organizational unit node and retain the field data so we
        // can verify it later.
        $nid = $this->createRandomOrganizationalUnit();
        $title = $this->editOrganizationalUnitPageRandomFiller->unitName;
        $email = $this->editOrganizationalUnitPageRandomFiller->email;
        $phone = $this->editOrganizationalUnitPageRandomFiller->phone;
        $fax = $this->editOrganizationalUnitPageRandomFiller->fax;
        $location_name = $this->editOrganizationalUnitPageRandomFiller->locationName;
        $location_street = $this->editOrganizationalUnitPageRandomFiller->locationStreet;
        $location_postalcode = $this->editOrganizationalUnitPageRandomFiller->locationPostalCode;
        $location_city = $this->editOrganizationalUnitPageRandomFiller->locationCity;
        $website = preg_replace('#^https?://#', '', $this->editOrganizationalUnitPageRandomFiller->website);

        $title_opening_hours = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createOpeningHoursSet($title_opening_hours);

        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->openingHours->fill($title_opening_hours);

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByValue($title_opening_hours);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a landing page.
        $this->contentCreationService->createLandingPage();

        // Add an organizational unit pane to the landing page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPageLayoutPage->checkArrival();
        $region = $this->landingPageLayoutPage->display->getRandomRegion();
        $organizational_unit_pane = new OrganizationalUnitPanelsContentType($this);

        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->selectContentType($organizational_unit_pane);

        // Select the node we just created.
        // Test if the dropdown works for the node path.
        $expected_suggestion = $title . ' (node/' . $nid . ')';

        $organizational_unit_pane->getForm()->organizationalUnitAutocompleteField->fill('node/' . $nid);
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
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextNotPresent($phone, $pane->getWebdriverElement());
        $this->assertTextNotPresent($fax, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_city, $pane->getWebdriverElement());
        $this->assertTextNotPresent($website, $pane->getWebdriverElement());

        // Verify that the filled in values remain if you edit the pane.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $this->assertEquals($expected_suggestion, $organizational_unit_pane->getForm()->organizationalUnitAutocompleteField->getContent());

        // Change the view mode.
        $radio = $organizational_unit_pane->getForm()->viewMode->medium;
        $radio->select();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($phone);

        // Verify that new data is shown.
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextPresent($phone, $pane->getWebdriverElement());
        $this->assertTextPresent($fax, $pane->getWebdriverElement());
        $this->assertTextPresent($website, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextNotPresent($location_city, $pane->getWebdriverElement());

        // Change the view mode.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $radio = $organizational_unit_pane->getForm()->viewMode->long;
        $radio->select();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($location_name);

        // Verify that all expected data is shown.
        $this->assertTextPresent($title, $pane->getWebdriverElement());
        $this->assertTextPresent($email, $pane->getWebdriverElement());
        $this->assertTextPresent($phone, $pane->getWebdriverElement());
        $this->assertTextPresent($fax, $pane->getWebdriverElement());
        $this->assertTextPresent($location_name, $pane->getWebdriverElement());
        $this->assertTextPresent($location_street, $pane->getWebdriverElement());
        $this->assertTextPresent($location_postalcode, $pane->getWebdriverElement());
        $this->assertTextPresent($location_city, $pane->getWebdriverElement());
        $this->assertTextPresent($website, $pane->getWebdriverElement());

        // Change the view mode one more time.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $radio = $organizational_unit_pane->getForm()->viewMode->longOpeningHours;
        $radio->select();
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Wait until the pane is refreshed.
        $this->waitUntilTextIsPresent($location_name);

        // Make sure that the opening hours set is visible.
        $this->assertTextPresent('Closed');

        // Get out of the edit page so that subsequent tests are not buggered by
        // an alert.
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Now go and delete the opening hours set.
        // This is to test if the  OU does not generate an error page.
        //see : https://one-agency.atlassian.net/browse/PADNAZ-61
        $this->cleanUpService = new CleanUpService($this);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        // Go to the front-end and assess you see the OU fields, not an error page.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($title);
    }

    /**
     * Tests when fields are left empty, no notices are being thrown.
     *
     * @group panes
     */
    public function testEmptyFields()
    {
        // Log in as editor.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');

        // Check for an OU, when only the title is filled out, that no notices are being thrown.
        $unfilled_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $nid = $this->contentCreationService->createOrganizationalUnit($unfilled_title);
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($unfilled_title);
        $this->frontendNodeViewPage->assertNoIconsRendered();

        // Create a landing page.
        $this->contentCreationService->createLandingPage();

        // Add an organizational unit pane to the landing page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPageLayoutPage->checkArrival();
        $region = $this->landingPageLayoutPage->display->getRandomRegion();
        $organizational_unit_pane = new OrganizationalUnitPanelsContentType($this);

        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->selectContentType($organizational_unit_pane);

        // Select the node we just created.
        $organizational_unit_pane->getForm()->organizationalUnitAutocompleteField->fill('node/' . $nid);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0);

        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));

        // Check for all pages on all levels that no notices are being thrown.
        $this->assertTextPresent($unfilled_title, $pane->getWebdriverElement());
        $elements = $this->elements(
            $this->using('xpath')->value('//div[@class="messages error"]')
        );
        $this->assertEquals(0, count($elements));
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent($unfilled_title);
        $this->frontendNodeViewPage->assertNoIconsRendered();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->landingViewPage->checkArrival();
        $this->assertTextPresent($unfilled_title);
        $this->frontendNodeViewPage->assertNoIconsRendered();
    }

    /**
     * Test content region panes with organizational unit settings.
     *
     * @group organizationalUnit
     * @group panes
     */
    public function testAddContentRegionPane()
    {
        $this->contentRegionConfigurationPage->go();

        // Add custom content panes to the 'organizational unit' content region display.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('organizational_unit')->editLink,
            $this->testContent['organizational_unit']['right'],
            $this->testContent['organizational_unit']['bottom']
        );

        // Create a new landing page. Continue on to the page layout.
        $nid = $this->contentCreationService->createLandingPage();
        $this->landingPageLayoutPage->go($nid);

        // Test all combinations of content types and regions.
        foreach (array('right', 'bottom') as $region) {
            // Make sure the editor is fully loaded.
            $this->landingPageLayoutPage->waitUntilPageIsLoaded();
            $pane = $this->contentRegionUtility->addContentRegionPane(
                'organizational_unit',
                $region,
                $this->landingPageLayoutPage->display
            );
            // Check that the test content is visible.
            $this->assertTextPresent($this->testContent['organizational_unit'][$region]);
            // Remove the pane.
            $pane->delete();
        }
        // Save the page, so that the following test is not confronted with an
        // alert box.
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the content region panes on organizational unit pages.
     *
     * @group organizationalUnit
     * @group panes
     */
    public function testContentRegionsOnOrganizationalUnit()
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
        $this->contentRegionConfigurationPage->getOverride('organizational_unit')->enable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('organizational_unit')->checkbox;
        $this->assertTrue($checkbox->selected());

        // Click 'Edit content for every organizational unit page' and add two panes: one to
        // the right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('organizational_unit')->editLink,
            $this->testContent['organizational_unit']['right'],
            $this->testContent['organizational_unit']['bottom']
        );

        // Create an organizational_unit. We end up on the administrative node view.
        $nid = $this->contentCreationService->createOrganizationalUnit();

        // Go to the front-end view and check that the chosen panes for the organizational unit
        // content regions are shown.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['organizational_unit']['right']);
        $this->assertTextPresent($this->testContent['organizational_unit']['bottom']);

        // Go to the layout page.
        $this->layoutPage->go($nid);

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
        // addition to the chosen panes for the organizational unit content
        // regions.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['organizational_unit']['right']);
        $this->assertTextPresent($this->testContent['organizational_unit']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go back to the global content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Set the checkbox to use global content settings and click 'Save.
        $this->contentRegionConfigurationPage->getOverride('organizational_unit')->disable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('organizational_unit')->checkbox;
        $this->assertFalse($checkbox->selected());

        // Go to the front page of the organizational unit. Check that the global panes
        // are now shown.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Check that the custom panes are still shown.
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Check that the simple contact page panes are not shown.
        $this->assertTextNotPresent($this->testContent['organizational_unit']['right']);
        $this->assertTextNotPresent($this->testContent['organizational_unit']['bottom']);
    }

    /**
     * Tests whether you can add panes to the extra content regions or not.
     *
     * @group panes
     * @group organizationalUnit
     */
    public function testOrganizationalUnitExtraContentRegions()
    {
        // Create an organizational_unit. We end up on the administrative node view.
        $nid = $this->contentCreationService->createOrganizationalUnit();

        // Go to the layout page.
        $this->layoutPage->go($nid);

        // Add a custom content pane to one of the extra panes.
        // I had to use this class since the Content region display
        // test class hardcoded the regions bottom and right...
        $display = new PanelsIPEDisplay($this);
        $regions = $display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $text = $this->alphanumericTestDataProvider->getValidValue(36);
        $custom_content_pane->body = $text;
        $regions['nested_4_h']->addPane($custom_content_pane);

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($text);
    }

    /**
     * Tests that the featured image field is tracked as reference.
     *
     * @group linkChecker
     */
    public function testOrganizationalUnitFeaturedImageFieldReference()
    {
        // Create a image atom use as logo.
        $atom = $this->assetCreationService->createImage();

        // Create a Organizational Unit node to test with.
        $nid = $this->contentCreationService->createOrganizationalUnit();

        // Add the atom as a logo.
        $this->editOrganizationalUnitPage->go($nid);
        $this->editOrganizationalUnitPage->featuredImage->selectAtom($atom['id']);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Ensure there is a reference record for this.
        $references = reference_tracker_get_inbound_references('scald_atom', $atom['id']);
        $expected_references = array('node' => array($nid));
        $this->assertEquals($expected_references, $references);
    }

    /**
     * Tests if the Contact Information pane is hidden when empty.
     */
    public function testContactInformationPaneNotShownWhenEmpty()
    {
        // Create an Organizational Unit node without content.
        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->frontendNodeViewPage->go($nid);

        // Check in the Front-End view if the selector exists.
        try {
            $xpath = '//div[contains(@class, "pane-contact-information")]/div[@class="pane-content"]';
            $this->elements($this->using('xpath')->value($xpath));
        } catch (\Exception $e) {
            // Do nothing.
        }

        // Create an Organizational Unit node with content.
        $nid2 = $this->createRandomOrganizationalUnit();
        $this->frontendNodeViewPage->go($nid2);
        $this->waitUntilElementIsDisplayed($xpath);
    }

    /**
     * Tests the creation of a contact person when paddle_OU is enabled.
     *
     * @group editing
     * @group organizationalUnit
     */
    public function testContactPersonWithOrganizationalUnit()
    {
        // Create an organizational unit.
        $ou_nid = $this->contentCreationService->createOrganizationalUnit();
        $this->editOrganizationalUnitPage->go($ou_nid);
        $this->editOrganizationalUnitPageRandomFiller->randomize();
        $this->editOrganizationalUnitPageRandomFiller->fill($this->editOrganizationalUnitPage);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->go($ou_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Then we enable CP back.
        $this->appService->enableApp(new ContactPerson);
        // Check if the Head of unit has been used to create a CP node.
        // We check the auto complete field, if it's not empty,
        // it means that a CP node has been created and used as entity reference.
        $this->editOrganizationalUnitPage->go($ou_nid);
        $cp_auto_complete = $this->editOrganizationalUnitPage->headOfUnitAutoComplete->getContent();
        // Assert that the auto complete field is not empty.
        $this->assertNotEmpty($cp_auto_complete);
        // Assert the value of the auto complete, which should contain the head of unit text.
        $this->assertContains($this->editOrganizationalUnitPageRandomFiller->headOfUnit, $cp_auto_complete);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->checkArrival();

        // Make sure the head of unit appears on the frontend as a link.
        $this->assertTextPresent($this->editOrganizationalUnitPageRandomFiller->headOfUnit);
        $auto_complete_array = explode(' ', $cp_auto_complete, 3);
        list($first_name) = $auto_complete_array[0];
        $xpath = '//div[contains(@class, "paddle-oup-head-unit")]/a';
        $this->assertContains(strtolower($first_name), $this->byXPath($xpath)->attribute('href'));

        // Log out and assert that the head of unit is not present.
        // It should not be present because the CP is not published.
        $this->userSessionService->logout();
        $this->frontendNodeViewPage->go($ou_nid);
        $this->assertTextNotPresent($this->editOrganizationalUnitPageRandomFiller->headOfUnit);
        $this->assertTextNotPresent('Managed by:');

        // Log back in.
        $this->userSessionService->login('ChiefEditor');

        // Publish the CP.
        $cp_nid = trim($auto_complete_array[2], '()');
        $this->administrativeNodeViewPage->go($cp_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Log out again.
        $this->userSessionService->logout();

        // Assert that the info is shown.
        $this->frontendNodeViewPage->go($ou_nid);
        $this->assertTextPresent($this->editOrganizationalUnitPageRandomFiller->headOfUnit);
        $this->assertContains(strtolower($first_name), $this->byXPath($xpath)->attribute('href'));

        // Log back in.
        $this->userSessionService->login('ChiefEditor');
        // Remove the contact person reference.
        $this->editOrganizationalUnitPage->go($ou_nid);
        $this->editOrganizationalUnitPage->headOfUnitAutoComplete->clear();
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Ensure head of unit is not shown on the frontend any more, not even
        // the old head of unit.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->checkArrival();
        $this->assertTextNotPresent('Managed by:');
    }

    /**
     * Creates different OU's and links them ot each other.
     *
     * @return array
     *  An array of node Ids and titles of the created OU's.
     */
    protected function createOrganizationalUnitParents()
    {
        $ou_first_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_first_reference_nid = $this->contentCreationService->createOrganizationalUnitViaUI($ou_first_reference_title);

        $ou_second_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_second_reference_nid = $this->contentCreationService->createOrganizationalUnitViaUI($ou_second_reference_title);

        $ou_third_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_third_reference_nid = $this->contentCreationService->createOrganizationalUnitViaUI($ou_third_reference_title);

        $ou_fourth_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_fourth_reference_nid = $this->contentCreationService->createOrganizationalUnitViaUI($ou_fourth_reference_title);

        $this->fillOrganizationalUnitParentEntity($ou_first_reference_nid, $ou_second_reference_title);
        $this->fillOrganizationalUnitParentEntity($ou_second_reference_nid, $ou_third_reference_title);
        $this->fillOrganizationalUnitParentEntity($ou_third_reference_nid, $ou_fourth_reference_title);
        // Last one takes the first one as parent to test the loop.
        $this->fillOrganizationalUnitParentEntity($ou_fourth_reference_nid, $ou_first_reference_title);

        return array(
            $ou_first_reference_nid => $ou_first_reference_title,
            $ou_second_reference_nid => $ou_second_reference_title,
            $ou_third_reference_nid => $ou_third_reference_title,
            $ou_fourth_reference_nid => $ou_fourth_reference_title,
        );
    }

    /**
     * Helper to fill the parent entity field for each node.
     *
     * @param int $ou_nid
     *  The node id of the ou being updated.
     * @param string $ou_parent_title
     *  The title of the parent entity.
     */
    protected function fillOrganizationalUnitParentEntity($ou_nid, $ou_parent_title)
    {
        $this->editOrganizationalUnitPage->go($ou_nid);
        $this->editOrganizationalUnitPage->parentEntity->fill($ou_parent_title);
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $autocomplete->pickSuggestionByValue($suggestions[0]);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Create a random organizational unit.
     *
     * @return int
     *   The nid of the organisation unit node.
     */
    protected function createRandomOrganizationalUnit()
    {
        $this->addContentPage->go();
        $this->addContentPage->createNode('OrganizationalUnit');
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editOrganizationalUnitPage->checkArrival();
        $this->editOrganizationalUnitPageRandomFiller->randomize();

        // Use the maximum number of allowed characters for each field to test
        // if the autocomplete field can handle this.
        $this->editOrganizationalUnitPageRandomFiller->unitName = $this->alphanumericTestDataProvider->getValidValue(8);

        $this->editOrganizationalUnitPageRandomFiller->fill($this->editOrganizationalUnitPage);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $this->administrativeNodeViewPage->getNodeIDFromUrl();
    }
}
