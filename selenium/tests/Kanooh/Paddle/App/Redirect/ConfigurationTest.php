<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Redirect\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Redirect;

use Kanooh\Paddle\Apps\Redirect;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRedirect\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\Redirect\RedirectDeleteModal;
use Kanooh\Paddle\Pages\Element\Redirect\RedirectModal;
use Kanooh\Paddle\Pages\Element\Redirect\RedirectImportModal;
use Kanooh\Paddle\Pages\Element\Redirect\RedirectTableRow;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ConfigurationTest
 * @package Kanooh\Paddle\App\Redirect
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AppsOverviewPage
     */
    protected $appsOverviewPage;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The front page.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The frontend view page.
     *
     * @var ViewPage
     */
    protected $frontViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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

        // Prepare some variables for later use.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->appsOverviewPage = new AppsOverviewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->frontPage = new FrontPage($this);
        $this->frontViewPage = new ViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->cleanUpService = new CleanUpService($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Redirect);
    }

    /**
     * Tests the configuration form.
     */
    public function testRedirectConfigurationForm()
    {
        // Delete all redirects that might have been created by other tests.
        $this->cleanUpService->deleteEntities('redirect');

        // Go to the configuration page and make sure the user is able to
        // manage the redirects.
        $this->configurePage->go();
        $this->assertTrue($this->configurePage->redirectTablePresent());
        $this->assertTextPresent('No URL redirects available. Click on "Create Redirect" to create one. You can also import URLs via a csv file.');

        // Verify that the filter field is not present.
        $this->assertFalse($this->configurePage->checkFilterPresent());
    }

    /**
     * Tests the add redirect functionality.
     */
    public function testRedirect()
    {
        // Create 2 nodes.
        $nid_1 = $this->contentCreationService->createBasicPage();
        $nid_2 = $this->contentCreationService->createBasicPage($this->alphanumericTestDataProvider->getValidValue());

        // Go to the configuration page and make sure the user is able to
        // manage the redirects.
        $this->configurePage->go();
        $initial_redirects = $this->configurePage->redirectTable->getRedirectTableRowCount();
        $this->configurePage->contextualToolbar->buttonCreateRedirect->click();

        $modal = new RedirectModal($this);
        $modal->waitUntilOpened();

        // Click the save button. The form should show some validation errors.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('From (original URL) field is required.');
        $this->waitUntilTextIsPresent('To (redirect URL) field is required.');

        // Fill in the required fields and submit again.
        $modal->form->from->fill('node/' . $nid_1);
        $modal->form->to->fill('node/' . $nid_2);
        $this->assertEquals(301, $modal->form->redirectStatus->getSelectedValue());
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The redirect has been saved.');

        // Check that a redirect is added.
        $this->assertEquals($initial_redirects + 1, $this->configurePage->redirectTable->getRedirectTableRowCount());
        // Verify no checkboxes for the bulk operations are present.
        $this->assertFalse($this->configurePage->redirectTable->checkRedirectSelectboxPresent());

        $rid = paddle_redirect_get_rid('node/' . $nid_1);

        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('node/' . $nid_1, $row->from);
        $this->assertEquals('node/' . $nid_2, $row->to);
        $this->assertEquals('301 Moved Permanently', $row->status);

        // Go to the url of the redirected path and verify you land on the
        // target path.
        $this->frontViewPage->go($nid_1);
        $this->frontViewPage->checkArrival();
        $this->assertEquals($nid_2, $this->frontViewPage->getNodeId());

        $nid_3 = $this->contentCreationService->createBasicPage($this->alphanumericTestDataProvider->getValidValue());
        // Test the edit of the redirect.
        $this->configurePage->go();
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $row->linkEdit->click();

        $modal = new RedirectModal($this);
        $modal->waitUntilOpened();

        // Change the to field.
        $this->assertEquals('node/' . $nid_1, $modal->form->from->getContent());
        $this->assertEquals('node/' . $nid_2, $modal->form->to->getContent());
        $modal->form->to->fill('node/' . $nid_3);
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The redirect has been saved.');

        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('node/' . $nid_1, $row->from);
        $this->assertEquals('node/' . $nid_3, $row->to);
        $this->assertEquals('301 Moved Permanently', $row->status);

        // Go to the url of the redirected path and verify you land on the
        // target path.
        $this->frontViewPage->go($nid_1);
        $this->frontViewPage->checkArrival();
        $this->assertEquals($nid_3, $this->frontViewPage->getNodeId());

        // Now delete the redirect.
        $this->configurePage->go();
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $row->linkDelete->click();

        $modal = new RedirectDeleteModal($this);
        $modal->waitUntilOpened();

        $this->assertTextPresent('Are you sure you want to delete the URL redirect from node/' . $nid_1 . ' to node/' . $nid_3 . '?');
        $modal->confirmButton->click();
        $modal->waitUntilClosed();

        $this->waitUntilTextIsPresent('The redirect has been deleted.');
        $this->assertFalse($this->configurePage->redirectTable->getRowByRid($rid));
    }

    /**
     * Tests the export CSV functionality.
     */
    public function testExportCSV()
    {
        // Create a redirect.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreateRedirect->click();

        $modal = new RedirectModal($this);
        $modal->waitUntilOpened();

        // Fill in the required fields and submit.
        $modal->form->from->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->form->to->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The redirect has been saved.');

        // Verify the export to CSV button is present.
        $this->assertTrue($this->configurePage->contextualToolbar->buttonExportCSV->displayed());

        // Send a request to export the CSV and verify the response is correct.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::POST);
        $request->setUrl($this->base_url . '/admin/paddlet_store/app/paddle_redirect/configure');
        $request->setData(array('op' => 'Export CSV'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);

        // Verify that after clicking the export button, that the other buttons
        // still work.
        $this->configurePage->contextualToolbar->buttonExportCSV->click();
        $this->configurePage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->appsOverviewPage->checkArrival();
    }

    /**
     * Tests the "Import redirects from CSV" functionality.
     */
    public function testImportCSV()
    {
        // Create a redirect.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonImportRedirect->click();

        $modal = new RedirectImportModal($this);
        $modal->waitUntilOpened();

        // Verify the help text is present.
        $this->assertTextPresent('You can import configuration for redirects via a CSV file. Use the import_template.csv');

        // Set the file and import it.
        $file = $this->file(dirname(__FILE__) . '/../../assets/redirects.csv');
        // We cannot use the chooseFile method of the FileField class here
        // because it tries to do a clear on the field. Our field does not
        // support a remove because it is not a field which keeps the last
        // chosen file because we delete the file instantly after importing.
        $file_field = $modal->form->importFile->getWebdriverElement();
        $file_field->value($file);
        $modal->form->importButton->click();
        $this->waitUntilTextIsPresent('The redirects have been imported.');

        // This file has 5 redirects so we should count 5 extra rows.
        $this->assertTextPresent('5 row(s) imported.');
        // Check that the error messages are printed.
        $this->assertTextPresent('Line 6 contains an invalid status code so used the default 301 code.');
        $this->assertTextPresent('Line 7 does not contain a source path or a redirect path.');
        $this->assertTextPresent('Line 8 does not contain a source path or a redirect path.');

        // Check that all required text is present for the imported redirects.
        $rid = paddle_redirect_get_rid('T1');
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('T1', $row->from);
        $this->assertEquals('T2', $row->to);
        $this->assertEquals('301 Moved Permanently', $row->status);

        // Test for when the status code is left empty in the import file.
        $rid = paddle_redirect_get_rid('T3');
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('T3', $row->from);
        $this->assertEquals('T4', $row->to);
        $this->assertEquals('301 Moved Permanently', $row->status);

        $rid = paddle_redirect_get_rid('T5');
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('T5', $row->from);
        $this->assertEquals('T6', $row->to);
        $this->assertEquals('303 See Other', $row->status);

        $rid = paddle_redirect_get_rid('T7');
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('T7', $row->from);
        $this->assertEquals('T8', $row->to);
        $this->assertEquals('307 Temporary Redirect', $row->status);

        // Test for when the status code is invalid in the import file.
        $rid = paddle_redirect_get_rid('T9');
        /** @var RedirectTableRow $row */
        $row = $this->configurePage->redirectTable->getRowByRid($rid);
        $this->assertEquals('T9', $row->from);
        $this->assertEquals('T10', $row->to);
        $this->assertEquals('301 Moved Permanently', $row->status);
    }
}
