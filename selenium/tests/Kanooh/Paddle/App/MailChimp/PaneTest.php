<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\PaneTest.
 */

namespace Kanooh\Paddle\App\MailChimp;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\Pane\SignupForm\SignupFormPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SignupFormPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MailChimpService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Signup Form pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

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
     * Landing page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Front end node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->adminViewPage = new AdminViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->mailChimpService = new MailChimpService($this, getenv('mailchimp_api_key'));
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new LayoutPage($this);
        $this->viewPage = new ViewPage($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new MailChimp);
    }

    /**
     * Tests the basic configuration and functionality of the Signup Form pane.
     *
     * @group panes
     * @group mailchimp
     */
    public function testPane()
    {
        // Make sure that the subscriptions are processed immediately.
        variable_set('mailchimp_cron', false);

        // Get the lists associated with this MailChimp account.
        $lists = $this->mailChimpService->getMailChimpLists();
        $list_names = array_values($lists);
        $list_ids = array_keys($lists);

        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Add new Signup form with one list only.
        $signup_id = $this->mailChimpService->createSignupFormUI($this->alphanumericTestDataProvider->getValidValue(8), array($list_names[0]));
        // Add the signup form to a pane in the node.
        $pane_uuid = $this->addSignupFormToPane($nid, $signup_id);
        // Check that the form is disabled on the backend.
        $this->assertSignupFormDisabled($pane_uuid);
        // Check the front-end functioning of the form.
        $this->viewPage->go($nid);
        $this->assertSignupForm(array($list_ids[0] => $list_names[0]), $pane_uuid);

        // Then add a Signup form with both lists.
        $signup_id = $this->mailChimpService->createSignupFormUI($this->alphanumericTestDataProvider->getValidValue(8), $list_names);
        // Add the signup form to a pane in the node.
        $pane_uuid = $this->addSignupFormToPane($nid, $signup_id);
        // Check that the form is disabled on the backend.
        $this->assertSignupFormDisabled($pane_uuid);
        // Check the front-end functioning of the form.
        $this->viewPage->go($nid);
        $this->assertSignupForm($lists, $pane_uuid);
    }

    /**
     * Add a Signup form as a pane to a node.
     *
     * @param string $nid
     *   The node id of the node to which to add the Signup Form pane.
     * @param string $signup_id
     *   The entity id of the Signup Form.
     *
     * @return string
     *   The pane uuid.
     */
    public function addSignupFormToPane($nid, $signup_id)
    {
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new SignupFormPanelsContentType($this);
        $callable = new SerializableClosure(
            function ($modal) use ($content_type, $signup_id) {
                $content_type->getForm()->signupForms[$signup_id]->select();
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        return $pane_uuid;
    }

    /**
     * Check the functioning of the Signup Forms rendered in a pane on the front-end.
     * @param array $lists
     *   Array containing the lists to which the users should be subscribed.
     * @param string $pane_uuid
     *   The uuid of the pane to which the form has been added.
     */
    public function assertSignupForm($lists, $pane_uuid)
    {
        // Get the pane element from the page.
        $signup_form_pane = new SignupFormPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');

        // Check the number of checkboxes in the form according to the number
        // of lists for the entity.
        $expected_checkboxes = count($lists) == 1 ? 0 : count($lists);
        $this->assertEquals($expected_checkboxes, $signup_form_pane->mainForm->getCheckboxCount());

        // If the form should have checkboxes make sure they match the lists.
        if (count($lists) > 1) {
            foreach ($lists as $list_name) {
                $this->assertTrue($signup_form_pane->mainForm->selectListByLabel($list_name));
            }
        }

        // Test that the email field is required.
        $signup_form_pane->mainForm->buttonSubmit->click();
        $this->waitUntilTextIsPresent('Email Address field is required.');

        // Test the validation of a woeful email address.
        $signup_form_pane->mainForm->fillInFieldByLabel('Email Address', $this->alphanumericTestDataProvider->getValidValue());
        $signup_form_pane->mainForm->buttonSubmit->click();
        $this->waitUntilTextIsPresent('is an invalid e-mail address.');

        // Now enter valid data and submit it to send the subscription.
        $email = $this->alphanumericTestDataProvider->getValidValue(5) . '@' . $this->alphanumericTestDataProvider->getValidValue(5) . '.com';
        $first_name = $this->alphanumericTestDataProvider->getValidValue(8);
        $last_name = $this->alphanumericTestDataProvider->getValidValue(8);
        $this->assertTrue($signup_form_pane->mainForm->fillInFieldByLabel('Email Address', $email));
        $this->assertTrue($signup_form_pane->mainForm->fillInFieldByLabel('First Name', $first_name));
        $this->assertTrue($signup_form_pane->mainForm->fillInFieldByLabel('Last Name', $last_name));
        $signup_form_pane->mainForm->buttonSubmit->click();
        // If the subscription has been successful, the MailChimp API will
        // return subscription info and show a success message.
        $this->waitUntilTextIsPresent('You have been successfully subscribed.');

        // We don't need to check if the subscription is already there
        // because MailChimp doesn't update the lists immediately and we
        // don't want to make the test wait for more than 90 seconds.
        /*foreach (array_keys($lists) as $list_id) {
            // Wait until MailChimp registers the subscription. This might take
            // a while so use higher timeout than usual.
            $mailchimp_service = $this->mailChimpService;
            $callable = new SerializableClosure(
                function () use ($mailchimp_service, $list_id, $email) {
                    if ($mailchimp_service->getListMember($list_id, $email)) {
                        return true;
                    }
                }
            );
            $this->waitUntil($callable, 90000);

            $member = $this->mailChimpService->getListMember($list_id, $email);
            $this->assertEquals($first_name, $member['FNAME']);
            $this->assertEquals($last_name, $member['LNAME']);
        }*/
    }

    /**
     * Checks that the signup forms are disabled when viewed from backend.
     *
     * @param string $pane_uuid
     *   The pane uuid.
     */
    public function assertSignupFormDisabled($pane_uuid)
    {
        // Get the pane element from the page.
        $signup_form_pane = new SignupFormPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $this->assertFalse($signup_form_pane->mainForm->buttonSubmit->enabled());
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
