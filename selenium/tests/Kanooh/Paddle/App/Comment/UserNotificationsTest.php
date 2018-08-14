<?php
/**
 * @file
 * Contains \Kanooh\Paddle\App\Comment\UserNotificationsTest.
 */

namespace Kanooh\Paddle\App\Comment;

use Kanooh\Paddle\Apps\Comment;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Pages\User\LoginPage\LoginPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserService;
use Kanooh\Paddle\Utilities\UserSessionService;
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
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

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
        $this->cleanUpService = new CleanUpService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->loginPage = new LoginPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->userService = new UserService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->appService->enableApp(new Comment);

        $drupal = new DrupalService();
        if (!$drupal->isBootstrapped()) {
            $drupal->bootstrap($this);
        }
    }

    /**
     * Tests the user notifications fields.
     */
    public function testUserAddCommentNotificationsField()
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

        // Verify that the comment notification is not checked by default.
        $notifications = $this->userProfileEditPage->form->notifications;
        // Verify that the others notifications are unchecked by default.
        $this->assertFalse($notifications->getByValue('comment_added_responsible')->isChecked());

        // Fill any possible missing profile data in order to be able to save the page.
        $this->userProfileEditPage->form->completeUserProfile();

        // Check the comment notification and save the profile.
        $notifications->getByValue('comment_added_responsible')->check();

        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Edit again the user and verify that the values are kept.
        $this->userProfileEditPage->go($account->uid);
        $notifications = $this->userProfileEditPage->form->notifications;
        $this->assertTrue($notifications->getByValue('comment_added_responsible')->isChecked());

        // Delete the user, to avoid notifications being generated for him.
        $this->userSessionService->logout();
        $this->cleanUpService->deleteEntities('user', false, array($account->uid));
    }

    /**
     * Test the notification sent to a responsible author on comment adding.
     */
    public function testCommentAddedNotification()
    {
        // Clean the system from all the available messages, so we can easily
        // find if the notification system is working.
        $this->cleanUpService->deleteEntities('message');
        $this->userSessionService->login('ChiefEditor');

        // Enable commenting for basic pages.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a basic page to use in the test.
        $nid = $this->contentCreationService->createBasicPage();
        // Create a new user.
        $new_user = $this->userService->createUser();

        // Add an editor as responsible author and open up commenting..
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->commentRadioButtons->open->select();
        $this->nodeEditPage->setNodeResponsibleAuthor($new_user->name);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the front end and add a comment.
        $this->frontEndNodeViewPage->go($nid);
        $this->frontEndNodeViewPage->addComment();

        // Verify that no notifications were created.
        $messages = $this->loadMessagesByType('paddle_notifications_comment_added_responsible');
        $this->assertCount(0, $messages);

        // Now enable this notification for the current user.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->notifications->getByValue('comment_added_responsible')->check();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved');

        // Set the user as responsible.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->setNodeResponsibleAuthor($this->userSessionService->getCurrentUserName());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Add another comment.
        $this->frontEndNodeViewPage->go($nid);
        $this->frontEndNodeViewPage->addComment();

        // Verify that no notifications were created, as the responsible author
        // is the one creating the note.
        $messages = $this->loadMessagesByType('paddle_notifications_comment_added_responsible');
        $this->assertCount(0, $messages);

        // Log as another user.
        $this->userSessionService->switchUser('Editor');

        // Add another comment.
        $this->frontEndNodeViewPage->go($nid);
        $this->frontEndNodeViewPage->addComment();

        // Verify that the notification was created.
        $messages = $this->loadMessagesByType('paddle_notifications_comment_added_responsible');
        $this->assertCount(1, $messages);

        // Verify message properties and fields.
        $message = end($messages);
        $wrapper = entity_metadata_wrapper('message', $message);
        $this->assertMessageProperties($message, array(
            'bundle' => 'paddle_notifications_comment_added_responsible',
            'user' => $this->userSessionService->getUserId('ChiefEditor'),
            'field_paddle_notifications_node' => $nid,
        ));

        // Login to reset the configuration of the paddlet.
        $this->userSessionService->logout();
        $this->userSessionService->login('ChiefEditor');

        // Restore the default value.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
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
