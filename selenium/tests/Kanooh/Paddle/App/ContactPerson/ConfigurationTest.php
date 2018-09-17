<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePageOuEnabled;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Contact Person paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
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
     * @var ConfigurePageOuEnabled
     */
    protected $configurePageOuEnabled;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContactPersonEditPage
     */
    protected $editPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->configurePageOuEnabled = new ConfigurePageOuEnabled($this);
        $this->editPage = new ContactPersonEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new ContactPerson);

        $this->cleanUpService->deleteEntities('node', 'contact_person');

        $app = new OrganizationalUnit;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
    }

    /**
     * Tests the contact person overview page.
     */
    public function testContactPersonOverview()
    {
        // Create a published and non-published contact person.
        $values = array(
            'Online' => array(),
            'Concept' => array(),
        );

        foreach (array_keys($values) as $status) {
            $values[$status] = array(
                'first_name' => $this->alphanumericTestDataProvider->getValidValue(),
                'last_name' => $this->alphanumericTestDataProvider->getValidValue(),
                'function' => $this->alphanumericTestDataProvider->getValidValue(),
                'organisation' => $this->alphanumericTestDataProvider->getValidValue(),
            );

            $values[$status]['nid'] = $this->contentCreationService->createContactPerson($values[$status]['first_name'], $values[$status]['last_name']);

            // Go to the node edit pages, fill out all needed fields which show in the
            // overview table.
            $this->editPage->go($values[$status]['nid']);
            $this->editPage->form->function->fill($values[$status]['function']);
            $this->editPage->form->organizationalUnitLevel3->fill($values[$status]['organisation']);
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            if ($status == 'Online') {
                $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
                $this->administrativeNodeViewPage->checkArrival();
            }
        }

        // Go to the configure page of the paddlet and verify everything is
        // correctly shown.
        $this->configurePage->go();
        $this->assertTrue($this->configurePage->contactPersonTable->isPresent());
        $this->assertCount(2, $this->configurePage->contactPersonTable->rows);

        foreach ($values as $status => $value) {
            $row = $this->configurePage->contactPersonTable->getRowByNid($value['nid']);
            $this->assertEquals($value['first_name'] . ' ' . $value['last_name'], $row->title);
            $this->assertEquals($value['function'], $row->function);
            $this->assertEquals($value['organisation'], $row->organisation);
            $this->assertEquals($status, $row->status);
        }

        $this->assertTrue($this->configurePage->exportXLS->displayed());
        $this->assertTrue($this->configurePage->exportCSV->displayed());

        // Send a request to export the CSV and verify the response is correct.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/admin/paddlet_store/app/paddle_contact_person/configure');
        $request->setData(array('op' => 'Export CSV'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);

        // Send a request to export the XLS and verify the response is correct.
        $this->configurePage->go();
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/admin/paddlet_store/app/paddle_contact_person/configure');
        $request->setData(array('op' => 'Export XLS'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);

        // Enable organizational unit paddlet.
        $this->appService->enableApp(new OrganizationalUnit());

        // The configuration page should give another export view back when OU is enabled.
        $this->configurePageOuEnabled->go();
        $this->assertTrue($this->configurePageOuEnabled->contactPersonTable->isPresent());
        $this->assertCount(2, $this->configurePageOuEnabled->contactPersonTable->rows);

        foreach ($values as $status => $value) {
            $row = $this->configurePageOuEnabled->contactPersonTable->getRowByNid($value['nid']);
            $this->assertEquals($value['first_name'] . ' ' . $value['last_name'], $row->title);
            $this->assertEquals($value['function'], $row->functionFieldCollection);
            $this->assertEquals($status, $row->status);
        }

        $this->assertTrue($this->configurePageOuEnabled->exportXLS->displayed());
        $this->assertTrue($this->configurePageOuEnabled->exportCSV->displayed());

        // Send a request to export the CSV and verify the response is correct.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/admin/paddlet_store/app/paddle_contact_person/configure');
        $request->setData(array('op' => 'Export CSV'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);

        // Send a request to export the XLS and verify the response is correct.
        $this->configurePage->go();
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/admin/paddlet_store/app/paddle_contact_person/configure');
        $request->setData(array('op' => 'Export XLS'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);
    }
}
