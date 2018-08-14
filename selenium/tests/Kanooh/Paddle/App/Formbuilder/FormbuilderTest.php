<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\FormbuilderTest.
 */

namespace Kanooh\Paddle\App\Formbuilder;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\BuildFormPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\ConditionalsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\ConfigureFormPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\DownloadFormPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\EmailsFormPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\WebForm2PdfPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\EmailNotificationEditPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\FormSubmissionsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\FormbuilderViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\FormBuilder\FormBuilderEditPage;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\ViewPage\FormbuilderViewPage as FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class FormbuilderTest
 * @package Kanooh\Paddle\App\Formbuilder
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FormbuilderTest extends WebDriverTestCase
{
    /**
     * The administrative node view page.
     *
     * @var FormbuilderViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The administrative node view page.
     *
     * @var FormBuilderEditPage
     */
    protected $formBuilderEditPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var BuildFormPage
     */
    protected $buildFormPage;

    /**
     * @var ConditionalsPage
     */
    protected $conditionalsPage;

    /**
     * @var ConfigureFormPage
     */
    protected $configureFormPage;

    /**
     * @var DownloadFormPage
     */
    protected $downloadFormPage;

    /**
     * @var EmailsFormPage
     */
    protected $emailsFormPage;

    /**
     * @var WebForm2PdfPage
     */
    protected $webform2PdfPage;

    /**
     * @var EmailNotificationEditPage
     */
    protected $emailNotificationEditPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontPage
     */
    protected $formbuilderViewPage;

    /**
     * @var FormSubmissionsPage
     */
    protected $formSubmissionsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->administrativeNodeViewPage = new FormbuilderViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->buildFormPage = new BuildFormPage($this);
        $this->formBuilderEditPage = new FormBuilderEditPage($this);
        $this->conditionalsPage = new ConditionalsPage($this);
        $this->formSubmissionsPage = new FormSubmissionsPage($this);
        $this->configureFormPage = new ConfigureFormPage($this);
        $this->downloadFormPage = new DownloadFormPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->emailsFormPage = new EmailsFormPage($this);
        $this->emailNotificationEditPage = new EmailNotificationEditPage($this);
        $this->formbuilderViewPage = new frontPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->emailTestDataProvider = new EmailTestDataProvider();
        $this->webform2PdfPage = new WebForm2PdfPage($this);

        $this->userSessionService->login('ChiefEditor');
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Formbuilder);
    }

    /**
     * Tests the creation of a custom form.
     */
    public function testCreateForm()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Go to the form builder page and add a textfield to the form.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->assertTrue($this->buildFormPage->checkCustomFormFieldPresent('textfield'));
        $this->buildFormPage->contextualToolbar->buttonSave->click();

        // Verify that in the admin node view that the textfield is shown.
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTrue($this->administrativeNodeViewPage->checkCustomFormFieldPresent('textfield'));
        $this->assertTextPresent('New textfield');

        // Verify that the "Form" button is shown in the contextual toolbar.
        $this->assertTrue($this->administrativeNodeViewPage->contextualToolbar->buttonForm->displayed());

        // Verify that on the front end node view that the textfield is shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->formbuilderViewPage->checkArrival();
        $this->formbuilderViewPage->checkCustomFormFieldPresent('textfield');
        $this->assertTextPresent('New textfield');
    }

    /**
     * Tests the creation of a custom form page and make sure the fields are visible for concept nodes.
     *
     * See https://one-agency.atlassian.net/browse/KANWEBS-5679
     */
    public function testFormBuilderPage()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $this->formBuilderEditPage->go($nid);
        $this->formBuilderEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go to the form builder page and set the body field.
        $this->formBuilderEditPage->go($nid);
        $this->formBuilderEditPage->formBuilderEditForm->body->setBodyText($body);
        $this->formBuilderEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent($body);
    }

    /**
     * Tests the form submissions.
     */
    public function testFormSubmissions()
    {
        // Give an editor the needed permissions.
        $paddle_roles = paddle_user_paddle_user_roles();
        $editor_rid = array_search('Editor', $paddle_roles);
        user_role_grant_permissions($editor_rid, array('access all webform results'));

        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Go to the form builder page and add a textfield to the form. Publish
        // the form afterwards.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->buildFormPage->contextualToolbar->buttonSave->click();

        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Logout and go to the the form as an anonymous user.
        $this->userSessionService->logout();
        $this->formbuilderViewPage->go($nid);

        // Fill out the form and submit it.
        $xpath = '//div[contains(@class, "webform-component-textfield")]//input[@class="form-text"]';
        $textfield_element = $this->formbuilderViewPage->getTextFieldElement($xpath);
        $textfield_element->fill($this->alphanumericTestDataProvider->getValidValue());

        $this->formbuilderViewPage->nextPage->click();
        $this->waitUntilTextIsPresent('Please review your submission. Your submission is not complete until you press the "Submit" button!');
        $this->formbuilderViewPage->submit->click();
        $this->waitUntilTextIsPresent('Thank you, your submission has been received.');

        // Log back in as chief editor and go to the form submissions page.
        $this->userSessionService->login('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);

        // Click the submissions button and verify that the submission is present.
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions')->click();
        $this->formSubmissionsPage->checkArrival();
        $this->waitUntilTextIsPresent('Anonymous');
        $this->assertTrue($this->formSubmissionsPage->submissionFound(1));

        // Verify that the operation links are present.
        foreach (array('view', 'edit', 'delete') as $operation) {
            $this->assertTrue(
                $this->formSubmissionsPage->isSubmissionOperationPresent(1, $operation),
                "The $operation operation link is missing for Chief Editor."
            );
        }

        // Log as editor to check that he cannot edit/delete submissions.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions')->click();
        $this->formSubmissionsPage->checkArrival();
        $this->assertTrue(
            $this->formSubmissionsPage->isSubmissionOperationPresent(1, 'view'),
            'The view operation link is missing for Editor.'
        );
        $this->assertFalse(
            $this->formSubmissionsPage->isSubmissionOperationPresent(1, 'edit'),
            'The edit operation link is showing for Editor.'
        );
        $this->assertFalse(
            $this->formSubmissionsPage->isSubmissionOperationPresent(1, 'delete'),
            'The delete operation link is showing for Editor.'
        );

        // Go back to the admin view so we test the presence of the toolbar
        $this->formSubmissionsPage->contextualToolbar->buttonBack->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Revoke the set permissions.
        user_role_revoke_permissions($editor_rid, array('access all webform results'));
    }

    /**
     * Tests the email components (notification) settings form.
     */
    public function testEmailNotificationSettings()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Add a radio button for later usage.
        $radios_label = $this->alphanumericTestDataProvider->getValidValue();
        $this->buildFormPage->go($nid);
        $this->buildFormPage->checkArrival();
        $this->buildFormPage->dragAndDrop('radios');
        $this->waitUntilTextIsPresent('one');
        $this->waitUntilTextIsPresent('two');
        $this->buildFormPage->setHighlightedCustomFormFieldTitle($radios_label);
        $this->buildFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // And to the emails page.
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Emails')->click();
        $this->emailsFormPage->checkArrival();

        // Add a new email.
        $email = $this->emailTestDataProvider->getValidValue();
        $this->emailsFormPage->form->addressRadio->select();
        $this->emailsFormPage->form->addressText->fill($email);
        $this->emailsFormPage->form->add->click();
        // We don't test anything else from here, except that the link is working
        // and that after save we are brought back to our pages.
        $this->emailNotificationEditPage->checkArrival();
        $this->emailNotificationEditPage->contextualToolbar->buttonSave->click();
        $this->emailsFormPage->checkArrival();

        // Verify that our email notification was added.
        $this->waitUntilTextIsPresent($email);

        // Add a new notification based on component.
        $this->emailsFormPage->form->componentRadio->select();
        $this->emailsFormPage->form->componentSelect->selectOptionByLabel($radios_label);
        $this->emailsFormPage->form->add->click();
        $this->emailNotificationEditPage->checkArrival();
        $this->emailNotificationEditPage->contextualToolbar->buttonSave->click();
        $this->emailsFormPage->checkArrival();

        // Verify that the component notification was added.
        $this->waitUntilTextIsPresent($radios_label);
    }

    /**
     * Tests the configuration page of the webform.
     */
    public function testConfigureForm()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Go the configure form page.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that a chief editor can access the submissions.
        $this->assertNotNull($this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions'));
        $this->assertNotNull($this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Download'));

        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Configure')->click();
        $this->configureFormPage->checkArrival();

        // Verify that as chief editor, you cannot access the shield
        // submissions element.
        try {
            $this->configureFormPage->form->shieldSubmissions;
            $this->fail('Chief editors or editors should not be able to see the shield submissions field.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        // Verify that the hidden fieldsets remain hidden.
        foreach ($this->configureFormPage->form->hiddenElements as $xpath) {
            $elements = $this->elements($this->using('xpath')->value($xpath));
            $this->assertTrue(empty($elements), $xpath);
        }

        // Verify that the submit button label button is inside the fieldset,
        // visible and it works.
        // This is done because we are moving that element around from a
        // fieldset to another, and we want to be sure
        // that it still works after repositioning.
        $value = $this->alphanumericTestDataProvider->getValidValue();
        $this->configureFormPage->form->submitButtonLabel->fill($value);
        $this->configureFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The form settings have been updated');
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Configure')->click();
        $this->configureFormPage->checkArrival();
        $this->assertEquals($value, $this->configureFormPage->form->submitButtonLabel->getContent());
        $this->configureFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Login as site manager and verify that you can access the shield
        // submissions element.
        $this->userSessionService->switchUser('SiteManager');
        $this->configureFormPage->go($nid);
        $this->assertTrue($this->configureFormPage->form->shieldSubmissions->isDisplayed());

        // Shield the submissions and verify that a chief editor has no access
        // to them.
        $this->configureFormPage->form->shieldSubmissions->check();
        $this->configureFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->assertNotNull($this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions'));
        $this->assertNotNull($this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Download'));

        $this->userSessionService->switchUser('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);

        try {
            $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions');
            $this->fail('When the submissions are shielded, a chief editor should not be able to access them.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        try {
            $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Download');
            $this->fail('When the submissions are shielded, a chief editor should not be able to access them.');
        } catch (\Exception $e) {
            // Do nothing.
        }
    }

    /**
     * Tests that the webform default content type is not created.
     */
    public function testWebformContentType()
    {
        $bundles = node_type_get_types();
        $this->assertFalse(isset($bundles['webform']));
    }

    /**
     * Tests the download form.
     */
    public function testDownload()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Go to the form builder page and add a textfield to the form. Publish
        // the form afterwards.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->buildFormPage->contextualToolbar->buttonSave->click();

        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Logout and go to the the form as an anonymous user.
        $this->userSessionService->logout();
        $this->formbuilderViewPage->go($nid);

        // Fill out the form and submit it.
        $xpath = '//div[contains(@class, "webform-component-textfield")]//input[@class="form-text"]';
        $textfield_element = $this->formbuilderViewPage->getTextFieldElement($xpath);
        $textfield_element->fill($this->alphanumericTestDataProvider->getValidValue());

        $this->formbuilderViewPage->nextPage->click();
        $this->waitUntilTextIsPresent('Please review your submission. Your submission is not complete until you press the "Submit" button!');
        $this->formbuilderViewPage->submit->click();
        $this->waitUntilTextIsPresent('Thank you, your submission has been received.');
        // Go the download form page.
        $this->userSessionService->login('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Download')->click();
        $this->downloadFormPage->checkArrival();

        // Try to download the file.
        $this->downloadFormPage->contextualToolbar->buttonDownload->click();
        $this->assertTextNotPresent('The specified range will not return any results.');
    }

    /**
     * Tests that the addition of Scald atoms works in the WYSIWYG editor on the
     * Configuration page.
     *
     * @group wysiwyg
     * @group regression
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-3564.
     */
    public function testAddingAtomsOnConfigurationForm()
    {
        // Create an image first.
        $asset_creation_service = new AssetCreationService($this);
        $image_data = $asset_creation_service->createImage();

        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Go the configure form page.
        $this->configureFormPage->go($nid);

        // Now try to add an atom to the WYSIWYG.
        $this->configureFormPage->form->confirmationMessage->waitUntilReady();
        $this->configureFormPage->form->confirmationMessage->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();

        $atom_id = $image_data['id'];
        $atom = $library_modal->library->getAtomById($atom_id);

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Double-click the image in the CKEditor.
        $test_case = $this;
        $image_xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
        $callable = new SerializableClosure(
            function () use ($test_case, $image_xpath) {
                $test_case->waitUntilElementIsPresent($image_xpath);
            }
        );
        $this->configureFormPage->form->confirmationMessage->inIframe($callable);

        // Now save and check that it is present on the confirmation page.
        $this->configureFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the form builder page and add a textfield to the form. Publish
        // the form afterwards.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver) {
                try {
                    $webdriver->assertTextPresent('New textfield');
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        $this->buildFormPage->contextualToolbar->buttonSave->click();

        // Submit the form, no need to enter anything in the textfield.
        $this->formbuilderViewPage->go($nid);
        $this->formbuilderViewPage->nextPage->click();
        $this->formbuilderViewPage->submit->click();
        $this->waitUntilElementIsPresent($image_xpath);
    }

    /**
     * Tests that the elements scroll block stays in sight after scrolling.
     *
     * @group regression
     */
    public function testFormbuilderBlockScroll()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Add enough elements to the page to make it scrollable.
        $this->buildFormPage->go($nid);
        for ($i = 0; $i < 7; $i++) {
            // Save the current page to actually wait for its complete reload.
            $page = $this->buildFormPage;
            $this->buildFormPage->datePaletteButton->click();
            $page->waitUntilPageIsLoaded();
        }

        // Take the last element and scroll to it.
        $elements = $this->buildFormPage->formElements;
        $this->assertcount(8, $this->buildFormPage->formElements);
        $this->moveto(end($elements));

        // Verify that we can still click the first buttons in the form palette
        // without need to scroll.
        $page = $this->buildFormPage;
        $callable = new SerializableClosure(
            function () use ($page) {
                try {
                    $page->datePaletteButton->click();
                    return true;
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return null;
                }
            }
        );
        $this->waitUntil($callable, $this->timeout);

        // Verify that the click went to the correct element.
        $page->waitUntilPageIsLoaded();
        $this->assertCount(9, $this->buildFormPage->formElements);
    }

    /**
     * Tests the conditionals page for webforms.
     */
    public function testConditionals()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // First make sure the conditionals page is not accessible if the webform
        // has no components.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        try {
            $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm
                ->getButtonInDropdown('Conditionals');
            $this->fail('Conditionals button found even if the webform has no components');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Do nothing, all is fine.
        }

        // Go to the page directly and make sure we get "Access denied".
        $this->conditionalsPage->go($nid);
        $this->assertTextPresent('Access Denied');
        $this->assertTextPresent('You are not authorized to access this page.');

        // Add some components now to be able to add conditionals for them.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->buildFormPage->contextualToolbar->buttonSave->click();

        // Go to the conditionals page to check the contextual toolbar.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Conditionals')->click();
        $this->conditionalsPage->checkArrival();
        $this->conditionalsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->conditionalsPage->go($nid);
        $this->conditionalsPage->contextualToolbar->buttonBack->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the WebForm2pdf page for webforms.
     */
    public function testWebForm2Pdf()
    {
        // Create a formbuilder page and add a text field to the form.
        $nid = $this->contentCreationService->createFormbuilderPage();
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->buildFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go to the webform2pdf settings and make sure the setting are available.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Generate PDF')->click();
        $this->webform2PdfPage->checkArrival();
        $this->assertTextPresent('Generate PDF Document.');
        $this->webform2PdfPage->generatePdf->check();
        $this->assertTextPresent('General Settings');
        $this->assertTextPresent('Content Of The PDF Document');
        $this->assertTextPresent('PDF Header');
        $this->assertTextPresent('PDF Footer');

        // Assert that the paddle_atom ckeditor plugin opens as it should. See #PADNAZ-84.
        $this->webform2PdfPage->bodyHeader->click();
        $this->webform2PdfPage->pageBody->waitUntilReady();

        $this->webform2PdfPage->pageBody->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $library_modal->close();
        $library_modal->waitUntilClosed();

        // Click save after you check the checkbox to generate PDFs.
        $this->webform2PdfPage->contextualToolbar->buttonSave->click();

        // Logout and go to the the form as an anonymous user.
        $this->userSessionService->logout();
        $this->formbuilderViewPage->go($nid);
        // Fill out the form and submit it.
        $xpath = '//div[contains(@class, "webform-component-textfield")]//input[@class="form-text"]';
        $text_field_element = $this->formbuilderViewPage->getTextFieldElement($xpath);
        $text_field_element->fill($this->alphanumericTestDataProvider->getValidValue());

        $this->formbuilderViewPage->nextPage->click();
        $this->waitUntilTextIsPresent('Please review your submission. Your submission is not complete until you press the "Submit" button!');
        $this->formbuilderViewPage->submit->click();
        $this->waitUntilTextIsPresent('Thank you, your submission has been received.');

        // Log back in as chief editor and go to the form submissions page.
        $this->userSessionService->login('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);

        // Click the submissions button and verify that the submission is present.
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions')->click();
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->checkArrival();

        // Click the submissions button and verify that the submission is present.
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Submissions')->click();

        // Make sure that there is a pdf download link present on the page.
        $this->assertTextPresent('download pdf');

        // Go to the WebForm2pdf page to check the contextual toolbar.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->dropdownButtonForm->getButtonInDropdown('Generate PDF')->click();
        $this->webform2PdfPage->checkArrival();
        $this->webform2PdfPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->webform2PdfPage->go($nid);
        $this->webform2PdfPage->contextualToolbar->buttonBack->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
