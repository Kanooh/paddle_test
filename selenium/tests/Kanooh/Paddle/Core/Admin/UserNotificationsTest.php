<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\UserNotificationsTest.
 */

namespace Kanooh\Paddle\Core\Admin;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\User\LoginPage\LoginPage;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests user notifications.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserNotificationsTest extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The login page.
     *
     * @var LoginPage
     */
    protected $loginPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var UserProfileEditPage
     */
    protected $userProfileEditPage;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->loginPage = new LoginPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->userService = new UserService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the user notifications fields.
     *
     * @group userProfile
     */
    public function testUserNotificationsFields()
    {
        $account = $this->userService->createUser();

        // Login as the new user.
        // @todo this should be done through the UserSessionService.
        $this->userSessionService->logout();
        $this->loginPage->go();
        $this->loginPage->loginForm->name->fill($account->name);
        $this->loginPage->loginForm->pass->fill('demo');
        $this->loginPage->loginForm->submit->click();
        $this->waitUntilElementIsDisplayed('//body[contains(@class, "logged-in")]');

        // We cannot use userSessionService::getCurrentUserId() because
        // the currentUser property for that class is protected.
        $this->userProfileEditPage->go($account->uid);

        // Verify that the correct notifications are checked by default.
        $notifications = $this->userProfileEditPage->form->notifications;
        $this->assertTrue($notifications->getByValue('page_assigned')->isChecked());
        // Verify that the others notifications are unchecked by default.
        $this->assertFalse($notifications->getByValue('page_expired')->isChecked());
        $this->assertFalse($notifications->getByValue('node_update_responsible')->isChecked());
        $this->assertFalse($notifications->getByValue('note_added_responsible')->isChecked());
        $this->assertFalse($notifications->getByValue('page_expiration')->isChecked());

        // Fill any possible missing profile data in order to be able to save the page.
        $this->userProfileEditPage->form->completeUserProfile();

        // Uncheck the needed notifications and save the profile.
        $notifications->getByValue('page_assigned')->uncheck();
        $notifications->getByValue('page_expired')->check();
        $notifications->getByValue('node_update_responsible')->check();
        $notifications->getByValue('note_added_responsible')->check();
        $notifications->getByValue('page_expiration')->check();

        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Edit again the user and verify that the values are kept.
        $this->dashboardPage->go();

        $this->userProfileEditPage->go($account->uid);
        $notifications = $this->userProfileEditPage->form->notifications;
        $this->assertFalse($notifications->getByValue('page_assigned')->isChecked());
        // Verify that the others notifications are checked.
        $this->assertTrue($notifications->getByValue('page_expired')->isChecked());
        $this->assertTrue($notifications->getByValue('node_update_responsible')->isChecked());
        $this->assertTrue($notifications->getByValue('note_added_responsible')->isChecked());
        $this->assertTrue($notifications->getByValue('page_expiration')->isChecked());


        // Delete the user, to avoid notifications being generated for him.
        $this->userSessionService->logout();
        $this->cleanUpService->deleteEntities('user', false, array($account->uid));
    }

    /**
     * Test the notification that should be sent when a page is assigned to an user.
     *
     * @group userProfile
     */
    public function testPageAssignedNotification()
    {
        // Create a basic page and go to its admin view.
        $nid = $this->contentCreationService->createBasicPage();

        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');

        // Assign the page to be checked by the demo_editor.
        $this->adminNodeViewPage->go($nid);
        $this->assignNodeToUser('ToEditor', 'demo_editor');

        // Load all the entities.
        $messages = entity_load('message', false, array(), true);

        // Assert that the message was created.
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = reset($messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_page_assigned',
            'user' => $this->userSessionService->getUserId('Editor'),
            'field_paddle_notifications_node' => $nid,
            'field_paddle_notifications_user' => $this->userSessionService->getCurrentUserId(),
        ));

        // Put the page in review for the chief editor.
        $this->assignNodeToUser('ToChiefEditor', 'demo_chief_editor');

        // Load all the entities again and extract the new ones.
        $old_messages = $messages;
        $messages = entity_load('message', false, array(), true);
        $new_messages = array_diff_key($messages, $old_messages);

        // Assert that the message was created.
        $this->assertCount(1, $new_messages);

        // Verify message properties and fields.
        $message = reset($new_messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_page_assigned',
            'user' => $this->userSessionService->getUserId('ChiefEditor'),
            'field_paddle_notifications_node' => $nid,
            'field_paddle_notifications_user' => $this->userSessionService->getCurrentUserId(),
        ));

        // Create a new user with disabled notifications.
        $new_user = $this->userService->createUser(array(
            'field_paddle_user_notifications' => array(),
        ));
        $this->userService->assignRolesToUser($new_user, array('Chief Editor'));

        // Reload the admin view and assign node to the new user.
        $this->adminNodeViewPage->reloadPage();
        $this->assignNodeToUser('ToChiefEditor', $new_user->name);

        // Load all the entities again and extract the new ones.
        $old_messages = $messages;
        $messages = entity_load('message', false, array(), true);
        $new_messages = array_diff_key($messages, $old_messages);

        // Assert that the message was not created.
        $this->assertCount(0, $new_messages);

        // Assign the page to the current user.
        $this->assignNodeToUser('ToChiefEditor', 'demo');

        // Load all the entities again and extract the new ones.
        $old_messages = $messages;
        $messages = entity_load('message', false, array(), true);
        $new_messages = array_diff_key($messages, $old_messages);

        // Assert that the message was not created.
        $this->assertCount(0, $new_messages);

        $this->cleanUpService->deleteEntities('user', false, array($new_user->uid));
    }

    /**
     * Tests the page expiration notification.
     *
     * @group userProfile
     */
    public function testPageExpirationNotification()
    {
        // Create a new user with disabled notifications.
        $this->userService->createUser(array(
            'field_paddle_user_notifications' => array(),
        ));

        // Make sure that the notification is set.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->notifications->getByValue('page_expiration')->check();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Create a node which will expire within 2 weeks.
        $nid = $this->contentCreationService->createBasicPage();
        $this->nodeEditPage->go($nid);
        $now = strtotime('+14 days');
        $this->nodeEditPage->unpublishOnDate->value(format_date($now, 'custom', 'd/m/Y'));
        $this->nodeEditPage->unpublishOnTime->clear();
        $this->nodeEditPage->unpublishOnTime->value(format_date($now, 'custom', 'H:i:s'));
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Invalidate the user cache to pick up correct emails.
        entity_get_controller('user')->resetCache();

        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');

        // Run the cron.
        paddle_notifications_cron();

        // Load all the entities.
        $messages = entity_load('message', false, array(), true);

        // Assert that only 1 message was created.
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = reset($messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_page_expiration',
            'user' => $this->userSessionService->getCurrentUserId(),
            'field_paddle_notifications_node' => $nid,
        ));

        // Remove the node to prevent issues with other tests.
        $this->cleanUpService->deleteEntities('node', false, array($nid));
    }

    /**
     * Tests the notification sent when a page has been unpublished by scheduler.
     *
     * @group userProfile
     */
    public function testPageExpiredNotification()
    {
        // Activate the notification for this user.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->notifications->getByValue('page_expired')->check();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Reset the user cache for the changes to be seen.
        $controller = entity_get_controller('user');
        $controller->resetCache();

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Set a depublication date.
        $this->nodeEditPage->go($nid);
        $date = format_date(strtotime('+1 days'), 'custom', 'd/m/Y');
        $this->nodeEditPage->unpublishOnDate->value($date);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // We cannot set the depublishing on the future, as the scheduler cron
        // will use anyway the REQUEST_TIME to fetch the nodes.
        db_update('scheduler')
            ->fields(array(
                'unpublish_on' => REQUEST_TIME - 86400,
            ))
            ->condition('nid', $nid)
            ->execute();

        // Create a new user, so we will be able to assert that the notification
        // is disabled by default.
        $this->userService->createUser();

        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');

        // Run the scheduler cron.
        scheduler_cron();

        // Verify that only the chief editor has been notified of the change.
        $messages = $this->loadMessagesByType('paddle_notifications_page_expired');
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = reset($messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_page_expired',
            'user' => $this->userSessionService->getCurrentUserId(),
            'field_paddle_notifications_node' => $nid,
        ));

        // Remove the node to prevent issues with other tests.
        $this->cleanUpService->deleteEntities('node', false, array($nid));
    }

    /**
     * Tests the notification sent to a responsible author on node changes.
     *
     * @group userProfile
     */
    public function testPageResponsibleNotification()
    {
        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Edit the node, without any responsible author assigned.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that no notifications have been generated.
        $messages = $this->loadMessagesByType('paddle_notifications_node_update_responsible');
        $this->assertCount(0, $messages);

        // Create a new user, and assign the page to him.
        $responsible = $this->userService->createUser();
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->setNodeResponsibleAuthor($responsible->name);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that no notifications have been generated, as the default
        // value for the field is off.
        $messages = $this->loadMessagesByType('paddle_notifications_node_update_responsible');
        $this->assertCount(0, $messages);

        // Login as demo_chief_editor, and enable the notification for him.
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case) {
                $test_case->userProfileEditPage->go($test_case->userSessionService->getUserId('ChiefEditor'));
                $test_case->userProfileEditPage->form->notifications->getByValue('node_update_responsible')->check();
                $test_case->userProfileEditPage->form->completeUserProfile();
                $test_case->userProfileEditPage->contextualToolbar->buttonSave->click();
                $test_case->waitUntilTextIsPresent('The changes have been saved');
            }
        );
        $this->userSessionService->runAsUser('ChiefEditor', $callable);

        // Assign the page to the chief editor.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->setNodeResponsibleAuthor('demo_chief_editor');
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the chief editor has been notified of the change.
        $messages = $this->loadMessagesByType('paddle_notifications_node_update_responsible');
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = reset($messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_node_update_responsible',
            'user' => $this->userSessionService->getUserId('ChiefEditor'),
            'field_paddle_notifications_node' => $nid,
            'field_paddle_notifications_user' => $this->userSessionService->getCurrentUserId(),
        ));

        // Login as editor.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');

        // Edit the page.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the latest change was notified to the responsible again.
        $old_messages = $messages;
        $messages = $this->loadMessagesByType('paddle_notifications_node_update_responsible');
        $new_messages = array_diff_key($messages, $old_messages);
        $this->assertCount(1, $new_messages);

        // Verify message properties and fields.
        $message = reset($new_messages);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_node_update_responsible',
            'user' => $this->userSessionService->getUserId('ChiefEditor'),
            'field_paddle_notifications_node' => $nid,
            'field_paddle_notifications_user' => $this->userSessionService->getUserId('Editor'),
        ));

        // Login as the responsible author.
        $this->userSessionService->logout();
        $this->userSessionService->login('ChiefEditor');

        // Edit the page.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that no notifications has been triggered as the responsible
        // author is the user making changes already.
        $old_messages = $messages;
        $messages = $this->loadMessagesByType('paddle_notifications_node_update_responsible');
        $new_messages = array_diff_key($messages, $old_messages);
        $this->assertCount(0, $new_messages);
    }

    /**
     * Test the notification sent to a responsible author on editorial note add.
     *
     * @group userProfile
     */
    public function testEditorialNodeCreated()
    {
        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');

        // Create a basic page to use in the test.
        $nid = $this->contentCreationService->createBasicPage();

        // Create a new user.
        $new_user = $this->userService->createUser();

        // Add an editor as responsible author.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->setNodeResponsibleAuthor($new_user->name);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go back to the node edit and add an editorial note.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->addEditorialNote();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that no notifications were created.
        $messages = $this->loadMessagesByType('paddle_notifications_note_added_responsible');
        $this->assertCount(0, $messages);

        // Now enable this notification for the current user.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->notifications->getByValue('note_added_responsible')->check();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Set the user as responsible.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->setNodeResponsibleAuthor($this->userSessionService->getCurrentUserName());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Add another editorial note.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->addEditorialNote();

        // Verify that no notifications were created, as the responsible author
        // is the one creating the note.
        $messages = $this->loadMessagesByType('paddle_notifications_note_added_responsible');
        $this->assertCount(0, $messages);

        // Log as another user.
        $this->userSessionService->switchUser('Editor');

        // Add again an editorial note.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->addEditorialNote();

        // Verify that the notification was created.
        $messages = $this->loadMessagesByType('paddle_notifications_note_added_responsible');
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = end($messages);
        $wrapper = entity_metadata_wrapper('message', $message);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_note_added_responsible',
            'user' => $this->userSessionService->getUserId('SiteManager'),
            'field_paddle_notifications_node' => $nid,
            'field_paddle_notifications_user' => $this->userSessionService->getCurrentUserId(),
            'field_paddle_notifications_msg' => $wrapper->field_paddle_notifications_msg->getIdentifier(),
        ));
    }

    /**
     * Load all the message entities by type.
     *
     * @param string $type
     *   The type (bundle) of the message entity.
     * @return array
     *   An array of fully loaded entities.
     */
    protected function loadMessagesByType($type)
    {
        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'message');
        $query->entityCondition('bundle', $type);
        $result = $query->execute();

        if (empty($result['message'])) {
            return array();
        }

        return entity_load('message', array_keys($result['message']));
    }

    /**
     * Assign a node to an user in a certain "status".
     *
     * @param string $status
     *   The dropdown status name. Either 'ToChiefEditor' or 'ToEditor'.
     * @param string $username
     *   The name of the user to assign the node to.
     */
    protected function assignNodeToUser($status, $username)
    {
        $dropdown = 'dropdownButton' . $status;
        $this->adminNodeViewPage->contextualToolbar->{$dropdown}->getButton()->click();
        $this->adminNodeViewPage->contextualToolbar->{$dropdown}->getButtonInDropdown($username)->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Assert Message properties values.
     *
     * @param \Message $message
     *   The Message entity.
     * @param array $properties
     *   An array of property values, keyed by property name.
     */
    protected function assertMessageProperties(\Message $message, array $properties)
    {
        /* @var \EntityMetadataWrapper $wrapper */
        $wrapper = entity_metadata_wrapper('message', $message);

        // Treat the bundle property differently.
        if (!empty($properties['bundle'])) {
            $this->assertEquals($properties['bundle'], $wrapper->getBundle());
            unset($properties['bundle']);
        }

        // Loop all remaining properties and verify their identifier.
        foreach ($properties as $name => $expected) {
            $actual = $wrapper->{$name}->getIdentifier();
            $this->assertEquals($expected, $actual, "The property $name is wrong.");
        }
    }
}
