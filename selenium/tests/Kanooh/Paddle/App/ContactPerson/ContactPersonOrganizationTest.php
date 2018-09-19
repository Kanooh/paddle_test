<?php
/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContactPersonOrganizationTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\CompanyInformationTableRow;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller;
use Kanooh\Paddle\Pages\Node\ViewPage\ContactPersonViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Contact Person containing OU.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContactPersonOrganizationTest extends WebDriverTestCase
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
     * @var AutoComplete
     */
    protected $autoComplete;

    /**
     * @var ContactPersonRandomFiller
     */
    protected $contactPersonRandomFiller;

    /**
     * @var ContactPersonViewPage
     */
    protected $contactPersonViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContactPersonEditPage
     */
    protected $editContactPersonPage;

    /**
     * @var EditOrganizationalUnitPage
     */
    protected $editOrganizationalUnitPage;

    /**
     * @var array
     */
    protected $testContent;

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
        $this->autoComplete = new AutoComplete($this);
        $this->contactPersonRandomFiller = new ContactPersonRandomFiller();
        $this->contactPersonViewPage = new ContactPersonViewPage($this);
        $this->editContactPersonPage = new ContactPersonEditPage($this);
        $this->editOrganizationalUnitPage = new EditOrganizationalUnitPage($this);

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
    }

    /**
     * Tests the automatic creation of organizational units.
     */
    public function testContactPersonOrganizationMigration()
    {
        // Delete all contact persons and organizational units.
        $cleanUpService = new CleanUpService($this);
        $cleanUpService->deleteEntities('node', 'contact_person');
        $cleanUpService->deleteEntities('node', 'organizational_unit');

        $duplicate_organization_name = 'test org';
        $duplicate_street = 'Korenmarkt 22';
        $duplicate_postal_code = '9000';
        $duplicate_city = 'Ghent';
        $mobilePhone = '0468 15 74 26';
        $officePhone = '02 891 28 36';

        $cp_1_nid = $this->contentCreationService->createContactPerson(
            'Jan',
            'Van Gent',
            array(
                'field_paddle_cp_ou_level_1' => $duplicate_organization_name,
                'field_paddle_cp_address' => array(
                    'thoroughfare' => 'Korenmarkt 22',
                    'postal_code' =>  '9000',
                    'locality' => 'Ghent',
                ),
                'field_paddle_cp_mobile_office' => $mobilePhone,
                'field_paddle_cp_phone_office' => $officePhone,
            )
        );
        $this->contentCreationService->createContactPerson(
            'Mieke',
            'Stroel',
            array(
                'field_paddle_cp_ou_level_1' => $duplicate_organization_name,
                'field_paddle_cp_address' => array(
                    'thoroughfare' => 'Korenmarkt 22',
                    'postal_code' =>  '9000',
                    'locality' => 'Ghent',
                ),
            )
        );
        $this->contentCreationService->createContactPerson(
            'Jacob',
            'Steelant',
            array(
                'field_paddle_cp_ou_level_1' => $duplicate_organization_name,
                'field_paddle_cp_address' => array(
                    'thoroughfare' => 'Groenstraat 8',
                    'postal_code' =>  '3000',
                    'locality' => 'Antwerp',
                ),
            )
        );
        $this->contentCreationService->createContactPerson(
            'Roger',
            'Coucke',
            array(
                'field_paddle_cp_ou_level_1' => 'another test org',
                'field_paddle_cp_address' => array(
                    'thoroughfare' => 'Markt 34',
                    'postal_code' =>  '1000',
                    'locality' => 'Brussels',
                ),
            )
        );

        // Enable the Organizational Unit App.
        $this->appService->enableApp(new OrganizationalUnit);

        // Assert 3 organizational units have been created out of 4
        // organization data sets.
        $query = new \EntityFieldQuery();
        $result = $query->entityCondition('entity_type', 'node')
            ->entityCondition('bundle', 'organizational_unit')
            ->execute();
        $this->assertCount(3, $result['node']);

        // Assert that 2 organizational units have been created with the same
        // name because 2 out of 3 with the same name had the same address.
        $query = new \EntityFieldQuery();
        $result = $query->entityCondition('entity_type', 'node')
            ->entityCondition('bundle', 'organizational_unit')
            ->propertyCondition('title', $duplicate_organization_name)
            ->execute();
        $this->assertCount(2, $result['node']);
        $ou_nid = current(array_keys($result['node']));

        // Verify the address information has been copied over.

        // Head back to the contact person edit page and assert that the fields have been migrated.
        $this->editContactPersonPage->go($cp_1_nid);
        /** @var CompanyInformationTableRow $row */
        $row = $this->editContactPersonPage->form->companyInformationTable->getOrganizationTableRowByTitleAndNid($duplicate_organization_name, $ou_nid);
        $this->moveto($row->getWebdriverElement());
        $this->assertEquals($duplicate_organization_name . ' (' . $ou_nid . ')', $row->organizationalUnit->getContent());
        $this->assertTrue($row->loadContactInfo->isChecked());
        // Show the contact information.
        $row->loadContactInfo->uncheck();
        // Verify the contact information.
        $this->assertEquals($duplicate_city, $row->city->getContent());
        $this->assertEquals($duplicate_postal_code, $row->postal_code->getContent());
        $this->assertEquals($duplicate_street, $row->street->getContent());

        // Verify that the phone numbers have been copied correctly.
        $this->assertEquals($mobilePhone, $row->mobile->getContent());
        $this->assertEquals($officePhone, $row->phone->getContent());

        // Head to the front end page and assert that the address from the OU is shown.
        $this->contactPersonViewPage->go($cp_1_nid);
        $this->assertTextPresent($duplicate_city);
        $this->assertTextPresent($duplicate_postal_code);
        $this->assertTextPresent($duplicate_street);
    }

    /**
     * Tests the creation of a contact person with different referenced OU's.
     *
     * @group contactPerson
     */
    public function testContactPersonWithManyOrganizationalUnits()
    {
        $cp_1_fn = $this->alphanumericTestDataProvider->getValidValue(4);
        $cp_1_ln = $this->alphanumericTestDataProvider->getValidValue(4);
        $cp_1_full = $cp_1_fn . ' ' . $cp_1_ln;
        $cp_1_nid = $this->contentCreationService->createContactPerson($cp_1_fn, $cp_1_ln);

        // @TODO refactor this in wie is wie v2.
        $manager_fn = $this->alphanumericTestDataProvider->getValidValue(4);
        $manager_ln = $this->alphanumericTestDataProvider->getValidValue(4);
        $this->contentCreationService->createContactPerson($manager_fn, $manager_ln);
        $manager_full=  $manager_fn . ' ' . $manager_ln;

        // Enable the Organizational Unit App.
        $this->appService->enableApp(new OrganizationalUnit);

        $organizational_units = $this->createOrganizationalUnitParents();

        $data = array(
            'info_1' => array(
                'email' => 'testorg@gmail.com',
                'street' => 'Korenmarkt 22',
                'postal_code' => '9000',
                'function' => 'Developer',
                'mobilePhone' => '0468 15 74 26',
                'office' => '3B',
                'organization' => reset($organizational_units),
                'head_of_unit' =>  $manager_full,
            ),
            'info_2' => array(
                'email' => 'testorg2@gmail.com',
                'street' => 'Blabla 33',
                'postal_code' => '3000',
                'function' => 'Master',
                'mobilePhone' => '0468 15 74 26',
                'office' => '3B',
                'organization' => end($organizational_units),
                'head_of_unit' =>  $manager_full,
            ),
        );

        $this->editContactPersonPage->go($cp_1_nid);

        foreach ($data as $info) {
            $this->fillContactPersonFieldCollections($info);
        }

        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->contactPersonViewPage->go($cp_1_nid);

        $this->assertTextPresent($cp_1_full);

        // As anonymous users we are not supposed to see the OU's when they are not published.
        // So go to frontend as anonymous and check. We should see nothing.
        $this->userSessionService->logout();
        $this->contactPersonViewPage->go($cp_1_nid);

        // Address of the first OU should be shown.
        $this->assertTextPresent($data['info_1']['email']);
        $this->assertTextPresent($data['info_1']['function']);
        $this->assertTextPresent($data['info_1']['postal_code']);
        $this->assertTextPresent($data['info_1']['office']);
        $this->assertTextNOTPresent($manager_full);

        // No OU info at all should be shown.
        foreach ($data as $info) {
            $this->assertTextNotPresent($info['organization']);
            $this->assertTextNotPresent('show contact information');
            $this->assertTextNotPresent('Other information');
            $this->assertTextNotPresent('Also works with');
        }

        $this->userSessionService->login('ChiefEditor');
        // Now publish them and make sure they appear as links.
        reset($organizational_units);
        $keys[] = key($organizational_units);
        end($organizational_units);
        $keys[] = key($organizational_units);

        foreach ($keys as $nid) {
            $this->administrativeNodeViewPage->go($nid);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        $this->contactPersonViewPage->go($cp_1_nid);
        $this->assertContains(strtolower($data['info_1']['organization']), $this->contactPersonViewPage->getOrganizationByTitle($data['info_1']['organization'])->attribute('href'));
        $this->assertTextPresent($data['info_1']['email']);
        $this->assertTextPresent($data['info_1']['street']);
        $this->assertTextPresent($data['info_1']['postal_code']);
        $this->assertTextPresent($data['info_1']['mobilePhone']);
        $this->assertTextPresent($data['info_1']['function']);
        $this->assertTextPresent($manager_full);
        $this->assertTextPresent('show contact information');
        $this->assertTextPresent('Other information');
        $this->assertTextPresent('Also works with');

        // Info from the second OU should not be visible now.
        $this->assertTextNotPresent($data['info_2']['function']);

        // Click on the show contact info link to change the visible information.
        $this->contactPersonViewPage->showContactInfoLink->click();

        // Make sure that the data of the second OU is now visible.
        $this->contactPersonViewPage->checkArrival();
        $this->assertTextPresent($data['info_2']['street']);
        $this->assertTextPresent($data['info_2']['postal_code']);
        $this->assertTextNotPresent($data['info_1']['street']);
        $this->assertTextNotPresent($data['info_1']['postal_code']);
    }

    /**
     * Creates different OU's and links them ot each other.
     *
     * @return array
     *  An array of node ids and titles of the created OU's.
     */
    protected function createOrganizationalUnitParents()
    {
        $ou_first_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_first_reference_nid = $this->contentCreationService->createOrganizationalUnit($ou_first_reference_title);

        $ou_second_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_second_reference_nid = $this->contentCreationService->createOrganizationalUnit($ou_second_reference_title);

        $ou_third_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_third_reference_nid = $this->contentCreationService->createOrganizationalUnit($ou_third_reference_title);

        $ou_fourth_reference_title = $this->alphanumericTestDataProvider->getValidValue(8);
        $ou_fourth_reference_nid = $this->contentCreationService->createOrganizationalUnit($ou_fourth_reference_title);

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
     * Helper to fill the parent entity field for each node.
     *
     * @param array $data
     *  The field values to fill in.
     */
    protected function fillContactPersonFieldCollections($data)
    {
        $this->editContactPersonPage->form->addOrganization();
        $rows = $this->editContactPersonPage->form->companyInformationTable->rows;
        $row = end($rows);
        $row->phone->fill($data['mobilePhone']);
        $row->office->fill($data['office']);
        $row->function->fill($data['function']);
        $row->email->fill($data['email']);
        $row->loadContactInfo->uncheck();
        $row->postal_code->fill($data['postal_code']);
        $row->street->fill($data['street']);
        $this->moveto($this->byClassName("field-name-field-paddle-featured-image"));

        $row->organizationalUnit->fill($data['organization']);
        $row->organizationalUnit->waitForAutoCompleteResults();
        $auto_complete = new AutoComplete($this);
        $auto_complete->waitUntilDisplayed();
        $auto_complete->pickSuggestionByPosition(0);

        $row->manager->fill($data['head_of_unit']);
        $row->organizationalUnit->waitForAutoCompleteResults();
        $auto_complete = new AutoComplete($this);
        $auto_complete->waitUntilDisplayed();
        $auto_complete->pickSuggestionByPosition(0);
    }
}
