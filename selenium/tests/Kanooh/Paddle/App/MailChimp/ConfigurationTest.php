<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\MailChimp;

use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage\SignupFormPage;
use Kanooh\Paddle\Pages\Element\MailChimp\ApiKeyModal;
use Kanooh\Paddle\Pages\Element\MailChimp\SignupFormDeleteModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\MailChimpService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the MailChimp paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{

    /**
     * @var SignupFormPage
     */
    protected $addSignupFormPage;

    /**
     * @var AlphanumericTestDataProvider;
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var SignupFormPage
     */
    protected $editSignupFormPage;

    /**
     * @var string
     */
    protected $mailchimpApiKey;

    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

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
        $this->addSignupFormPage = new SignupFormPage($this, 'admin/config/services/mailchimp/signup/add');
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->editSignupFormPage = new SignupFormPage($this, 'admin/config/services/mailchimp/signup/manage/%');
        $this->mailChimpService = new MailChimpService($this, getenv('mailchimp_api_key'));
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MailChimp);

        // Get the mailchimp API key.
        $this->mailchimpApiKey = getenv('mailchimp_api_key');
    }

    /**
     * Tests the modal that allows the users to edit the API key.
     */
    public function testApiKey()
    {
        // Check the value of the API key. They always end on "us10".
        $this->assertEquals(substr($this->mailchimpApiKey, -4), 'us10');

        // Unset current api key value.
        variable_del('mailchimp_api_key');

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Verify that if the Drupal variable 'mailchimp_api_key' is not set we
        // get an error message on the paddlet config page.
        $this->configurePage->go();
        // This text will appear only if the API key is set but there are no Signup forms.
        $this->assertTextNotPresent('No Signup forms created.');
        $this->assertTextPresent('Before we can start, you need to enter your MailChimp API key in the settings. Click the "API KEY" button above.');

        // Try adding the API key now.
        $this->configurePage->contextualToolbar->buttonEditApiKey->click();

        $api_key_modal = new ApiKeyModal($this);
        $api_key_modal->waitUntilOpened();

        // First leave empty.
        $api_key_modal->form->apiKey->fill('');
        $api_key_modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('API key field is required.');

        // Then enter invalid value.
        $api_key_modal->form->apiKey->fill('abc');
        $api_key_modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The provided API key is not valid.');

        // Then enter the developer API key.
        $api_key_modal->form->apiKey->fill($this->mailchimpApiKey);
        $api_key_modal->form->saveButton->click();
        $api_key_modal->waitUntilClosed();
        $this->assertTextNotPresent('Before we can start, you need to enter your MailChimp API key in the settings. Click the "API KEY" button above.');

        // Finally verify that the value was correctly set.
        $this->configurePage->contextualToolbar->buttonEditApiKey->click();
        $api_key_modal = new ApiKeyModal($this);
        $api_key_modal->waitUntilOpened();

        $this->assertEquals($this->mailchimpApiKey, $api_key_modal->form->apiKey->getContent());
    }

    /**
     * Test the display, creation, editing and deletion of Signup forms.
     */
    public function testSignupForms()
    {
        // Set the API key and retrieve the list names.
        variable_set('mailchimp_api_key', $this->mailchimpApiKey);
        $list_names = array();
        foreach (mailchimp_get_lists() as $list) {
            $list_names[] = $list['name'];
        }

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Add new Signup form.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreateSignupForm->click();
        $this->addSignupFormPage->checkArrival();
        // Assert that some changes on the form are there.
        $this->assertHiddenFieldsAndLabels();
        $first_signup_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->addSignupFormPage->signupFormForm->title->fill($first_signup_title);
        $this->addSignupFormPage->signupFormForm->selectListByName($list_names[0]);
        $this->addSignupFormPage->contextualToolbar->buttonSave->click();

        // Verify that now we have signup forms table and the signup form was
        // created.
        $this->configurePage->checkArrival();
        $this->assertTextNotPresent('No Signup forms created.');
        $this->assertNotNull($this->configurePage->signupFormsTable);
        $row = $this->configurePage->signupFormsTable->getRowByTitle($first_signup_title);
        $this->assertEquals($row->lists, $list_names[0]);

        // Try editing the Signup Form and make changes but cancel and check
        // that nothing changed.
        $row->linkEdit->click();
        $this->editSignupFormPage->checkArrival();
        $this->editSignupFormPage->signupFormForm->title->fill($this->alphanumericTestDataProvider->getValidValue());
        $this->editSignupFormPage->signupFormForm->selectListByName($list_names[1]);
        $this->editSignupFormPage->contextualToolbar->buttonBack->click();
        $this->configurePage->checkArrival();
        $row = $this->configurePage->signupFormsTable->getRowByTitle($first_signup_title);
        $this->assertEquals($row->lists, $list_names[0]);

        // Now edit it and make sure the changes are applied.
        $row = $this->configurePage->signupFormsTable->getRowByTitle($first_signup_title);
        $row->linkEdit->click();
        $this->editSignupFormPage->checkArrival();
        $new_signup_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->editSignupFormPage->signupFormForm->title->fill($new_signup_title);
        $this->editSignupFormPage->signupFormForm->selectListByName($list_names[1]);
        $this->editSignupFormPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $row = $this->configurePage->signupFormsTable->getRowByTitle($new_signup_title);
        // Now both lists should be checked.
        $this->assertEquals($row->lists, implode(', ', $list_names));

        // Create a second signup form.
        $this->configurePage->contextualToolbar->buttonCreateSignupForm->click();
        $this->addSignupFormPage->checkArrival();
        $second_signup_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->addSignupFormPage->signupFormForm->title->fill($second_signup_title);
        $this->addSignupFormPage->signupFormForm->selectListByName($list_names[0]);
        $this->addSignupFormPage->signupFormForm->selectListByName($list_names[1]);
        $this->addSignupFormPage->contextualToolbar->buttonSave->click();

        // Verify that the second signup form was created.
        $this->configurePage->checkArrival();
        $row = $this->configurePage->signupFormsTable->getRowByTitle($second_signup_title);
        $this->assertEquals($row->lists, implode(', ', $list_names));

        // Try deleting this Signup Form but close the modal without doing it.
        $row->linkDelete->click();
        $delete_modal = new SignupFormDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->close();
        // Make sure the Signup form is still there.
        $row = $this->configurePage->signupFormsTable->getRowByTitle($second_signup_title);
        $this->assertTextNotPresent('MailChimp Signup Form ' . $second_signup_title . ' has been deleted.');

        // Now really delete it. First get the initial number of rows in the tale.
        $start_number = $this->configurePage->signupFormsTable->getNumberOfRows();
        $row->linkDelete->click();
        $delete_modal = new SignupFormDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->waitUntilTextIsPresent('MailChimp Signup Form ' . $second_signup_title . ' has been deleted.');
        $final_number = $this->configurePage->signupFormsTable->getNumberOfRows();

        // Check that the row was deleted.
        $this->assertEquals($start_number - 1, $final_number);
    }


    /**
     * Test deletion of multiple Signup forms. Regression test for
     * https://one-agency.atlassian.net/browse/KANWEBS-3111.
     *
     * @group mailchimp
     * @group regression
     */
    public function testDeletingMultipleSignupForms()
    {
        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        $first_signup_title = $this->alphanumericTestDataProvider->getValidValue();
        $second_signup_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->mailChimpService->createSignupFormUI($first_signup_title);
        $this->mailChimpService->createSignupFormUI($second_signup_title);

        $this->configurePage->go();
        $start_number = $this->configurePage->signupFormsTable->getNumberOfRows();

        $this->assertTrue($this->mailChimpService->deleteSignupFormUI($first_signup_title));
        $this->assertTrue($this->mailChimpService->deleteSignupFormUI($second_signup_title));

        if ($start_number == 2) {
            $this->assertTextPresent('No Signup forms created.');
        } else {
            $final_number = $this->configurePage->signupFormsTable->getNumberOfRows();
            // Check that the row was deleted.
            $this->assertEquals($start_number - 2, $final_number);
        }
    }

    /**
     * Asserts that certain fields we want to hide are invisible and that some
     * field labels were changed.
     */
    public function assertHiddenFieldsAndLabels()
    {
        // Check that these fields are no visible.
        $hidden_fields_xpaths = array(
            '//div[contains(@class, "form-item-mode")]',
            '//div[contains(@class, "form-item-include-interest-groups")]',
            '//div[contains(@class, "form-item-settings-destination")]',
            '//div[@id="edit-save"]',
            '//div[@id="edit-cancel"]',
            '//div[@id="edit-delete"]',
        );
        foreach ($hidden_fields_xpaths as $xpath) {
            $found_fields = $this->elements($this->using('xpath')->value($xpath));
            if (count($found_fields)) {
                foreach ($found_fields as $field) {
                    $this->assertFalse($field->displayed());
                }
            }
        }

        // Check that these labels have been changed or are no longer visible.
        $removed_labels = array(
            'Display Mode',
            'Form destination page',
            'MailChimp List Selection & Configuration',
            'Subscription Settings',
            'Merge Field Display',
        );
        foreach ($removed_labels as $label) {
            $this->assertTextNotPresent($label);
        }
        $new_labels = array('Labels', 'Lists');
        foreach ($new_labels as $label) {
            $this->assertTextPresent(strtoupper($label));
        }
    }
}
