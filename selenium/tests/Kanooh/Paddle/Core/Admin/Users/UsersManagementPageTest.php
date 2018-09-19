<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\Users\UsersManagementPageTest.
 */

namespace Kanooh\Paddle\Core\Admin\Users;

use Kanooh\Paddle\Pages\Admin\Users\UsersPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileAddPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\UserService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Users Management page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UsersManagementPageTest extends WebDriverTestCase
{
    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var UserProfileEditPage
     */
    protected $userProfileEditPage;

    /**
     * @var UsersPage
     */
    protected $usersPage;

    /**
     * @var UserProfileAddPage
     */
    protected $userProfileAddPage;

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
        // Create some instances to use later on.
        $this->cleanUpService = new CleanUpService($this);
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->usersPage = new UsersPage($this);
        $this->userProfileAddPage = new UserProfileAddPage($this);
        $this->userService = new UserService($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests The availability of the users management page and the bulk operations.
     *
     * @group userManagement
     */
    public function testUsersManagementBulkOperations()
    {
        // As we are logged in as Editor, we are not considered to have access to the users management page.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');
        // Make sure users is not a menu link.
        $this->assertTextNotPresent('Users');

        $this->usersPage->go();
        $this->assertTextPresent('Access Denied');
        $this->userSessionService->logout();

        // Login as a Site Manager.
        $this->userSessionService->login('SiteManager');

        $this->usersPage->go();
        // Make sure users is a menu link.
        $xpath = '//ul/li[contains(@class, "ml-path-admin-users")]/a';
        $this->assertContains('admin/users', $this->byXPath($xpath)->attribute('href'));
        $this->assertTextPresent('users');
        $this->assertTextPresent('EDIT LINK');
        $this->assertTextPresent('LAST ACCESS');
        $this->assertTextPresent('MEMBER FOR');
        $this->assertTextPresent('E-MAIL');
        $this->assertTextPresent('USERNAME');

        $this->usersPage->form->selectAll->check();
        $this->usersPage->form->operations->selectOptionByValue('action::user_block_user_action');
        $this->usersPage->form->execute->click();
        // Make sure you get an error that you didn't choose a user.
        $this->waitUntilTextIsPresent('Are you sure you want to perform Block current user on the selected items?');
        $this->usersPage->form->cancel->click();
        // Make sure you come back to the management page.
        $this->usersPage->checkArrival();
        $this->waitUntilTextIsPresent('USERNAME');

        $this->usersPage->form->selectAll->check();
        $this->usersPage->form->operations->selectOptionByValue('action::views_bulk_operations_user_roles_action');
        $this->usersPage->form->execute->click();
        $this->waitUntilTextIsPresent('Add roles');
        $this->waitUntilTextIsPresent('Remove roles');

        $this->usersPage->go();

        $this->usersPage->form->selectAll->check();
        $this->usersPage->form->operations->selectOptionByValue('action::user_unblock_user_action');
        $this->usersPage->form->execute->click();
        $this->waitUntilTextIsPresent('Are you sure you want to perform Unblock current user on the selected items?');
    }

    /**
     * Tests The filter by role.
     *
     * @group userManagement
     */
    public function testUsersManagementRolesFilter()
    {
        $this->usersPage->go();
        // Select the read only role.
        $this->usersPage->form->rolesFilter->selectOptionByValue('6');
        $this->usersPage->form->apply->click();
        // Other user names with other roles must not be visible.
        $this->assertTextNotPresent('Chief Editor, Site Manager');
        $this->assertTextNotPresent('demo_chief_editor');
        $this->assertTextNotPresent('demo_editor');

        // Check the edit link.
        $xpath = '//tbody/tr/td[contains(@class, "views-field-edit-node")]/a';
        $this->assertContains('edit?destination=admin/users', $this->byXPath($xpath)->attribute('href'));

        // Select the editor role.
        $this->usersPage->form->rolesFilter->selectOptionByValue('4');
        $this->usersPage->form->apply->click();
        // Other user names with other roles must not be visible.
        $this->assertTextNotPresent('Chief Editor, Site Manager');
        $this->assertTextNotPresent('demo_chief_editor');
        $this->assertTextNotPresent('demo_read_only');

        // Check the edit link.
        $this->assertContains('edit?destination=admin/users', $this->byXPath($xpath)->attribute('href'));

        // Select the editor role.
        $this->usersPage->form->rolesFilter->selectOptionByValue('5');
        $this->usersPage->form->apply->click();
        // Other user names with other roles must not be visible.
        $this->assertTextPresent('Chief Editor, Site Manager');
        $this->assertTextPresent('demo_chief_editor');
        $this->assertTextNotPresent('demo_read_only');
        $this->assertTextNotPresent('demo_editor');

        // Check the edit link.
        $this->assertContains('edit?destination=admin/users', $this->byXPath($xpath)->attribute('href'));
    }

    /**
     * Tests The filter by status.
     *
     * @group userManagement
     */
    public function testUsersManagementStatusFilter()
    {
        $this->usersPage->go();
        // Filter on active users.
        $this->usersPage->form->statusFilter->selectOptionByValue('1');
        $this->usersPage->form->apply->click();
        $this->assertTextPresent('Chief Editor, Site Manager');
        $this->assertTextPresent('demo_chief_editor');
        $this->assertTextPresent('demo_editor');
        $this->assertTextPresent('demo');
        $this->assertTextPresent('demo_read_only');

        // Filter on non-active users
        $this->usersPage->form->statusFilter->selectOptionByValue('0');
        $this->usersPage->form->apply->click();
        $this->assertTextNotPresent('Chief Editor, Site Manager');
        $this->assertTextNotPresent('demo_chief_editor');
        $this->assertTextNotPresent('demo_editor');
        $this->assertTextNotPresent('demo_read_only');
    }

    /**
     * Tests that the site managers and editor can not access user/1/edit edit form.
     *
     * @group userManagement
     */
    public function testAccessUserOne()
    {
        // Site manager should have no permission to visit user/1/edit page.
        $this->userProfileEditPage->go(1);
        $this->assertTextPresent('Access Denied');

        // Editor should have no permission to visit user/1/edit page.
        $this->userSessionService->logout();
        $this->userSessionService->login('Editor');

        $this->userProfileEditPage->go(1);
        $this->assertTextPresent('Access Denied');

        $this->userSessionService->logout();
    }

    /**
     * Tests the user create button.
     *
     * @group userManagement
     */
    public function testUsersManagementUserCreation()
    {
        $this->usersPage->go();

        // Click on add user button.
        $this->usersPage->contextualToolbar->buttonAdd->click();
        $this->userProfileAddPage->checkArrival();

        // Fill the user registration form.
        $this->userProfileAddPage->form->completeUserProfile();

        // Make sure Notify user of new account is checked.
        $this->assertTrue($this->userProfileAddPage->form->notify->isChecked());
        // Test if the roles are shown.
        $this->assertTrue($this->userProfileAddPage->form->roles->isDisplayed());
        $this->userProfileAddPage->contextualToolbar->buttonSave->click();

        // Make sure you are redirected to user management page.
        $this->usersPage->checkArrival();
        $this->assertTextPresent('A welcome message with further instructions has been e-mailed to the new user');
    }

    /**
     * Test the limitations on users.
     */
    public function testUserLimit()
    {
        // Delete all custom roles first.
        $roles = paddle_user_custom_user_roles();

        if (!empty($roles)) {
            foreach (array_keys($roles) as $rid) {
                user_role_delete($rid);
            }
        }

        // Set the subscription to instap for testing purposes.
        variable_set('paddle_store_subscription_type', 'instap');

        // Create 3 users with a paddle role assigned to hit the user limit.
        $users = array();
        $user_store = paddle_user_store();

        // Create an active user without any paddle roles assigned.
        $user_no_roles = $this->userService->createUser();
        $users[] = $user_no_roles->uid;

        if ($user_store->usersLeft()) {
            for ($i = 0; $i < $user_store->usersLeft(); $i++) {
                $user = $this->userService->createUser();
                $this->userService->assignRolesToUser($user, array('Chief Editor'));
                $users[] = $user->uid;
            }
        }

        $roles = paddle_user_paddle_user_roles();
        // Now try to add another user via the interface.
        $this->usersPage->go();
        $this->usersPage->contextualToolbar->buttonAdd->click();
        $this->userProfileAddPage->checkArrival();
        $this->assertTextPresent('You are on subscription plan "go". You cannot create any more accounts. Please disable accounts or upgrade to a higher subscription plan.');
        $this->userProfileAddPage->form->completeUserProfile();
        $this->assertFalse($this->userProfileAddPage->form->roles->getByValue(current(array_keys($roles)))->isEnabled());
        $this->userProfileAddPage->contextualToolbar->buttonSave->click();

        // Check the warning message.
        $this->usersPage->checkArrival();
        $this->assertTextPresent('You are on subscription plan "go". You cannot create any more accounts. Please disable accounts or upgrade to a higher subscription plan.');

        // Check the warning message on the user edit page.
        $this->userProfileEditPage->go($user_no_roles->uid);
        $this->assertTextPresent('You are on subscription plan "go". You cannot create any more accounts. Please disable accounts or upgrade to a higher subscription plan.');
        $this->assertFalse($this->userProfileEditPage->form->roles->getByValue(current(array_keys($roles)))->isEnabled());

        // Small test for the bulk action to assign roles to a user.
        $this->usersPage->go();
        $this->usersPage->form->selectAll->check();
        $this->usersPage->form->operations->selectOptionByValue('action::views_bulk_operations_user_roles_action');
        $this->usersPage->form->execute->click();
        $this->waitUntilTextIsPresent('Remove roles');
        $this->assertTextPresent('You are on subscription plan "go". You cannot create any more accounts. Please disable accounts or upgrade to a higher subscription plan.');
        $this->assertTextNotPresent('Add roles');

        $this->usersPage->go();

        // Delete the newly created users to not interfere with future tests.
        $this->cleanUpService->deleteEntities('user', false, $users);

        // Delete the variable.
        variable_set('paddle_store_subscription_type', 'standaard');
    }

    /**
     * Ensure users without a Paddle user role also do not get Access Denied.
     *
     * @group userManagement
     */
    public function testNonPaddleUserRedirect()
    {
        $account = $this->userService->createUser();
        $this->userSessionService->logout();
        $this->userSessionService->customLogin($account->name);
        $this->assertTextNotPresent('Access Denied');
    }
}
