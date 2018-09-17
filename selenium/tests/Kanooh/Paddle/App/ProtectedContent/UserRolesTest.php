<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ProtectedContent\UserRolesTest.
 */

namespace Kanooh\Paddle\App\ProtectedContent;

use Kanooh\Paddle\Apps\ProtectedContent;
use Kanooh\Paddle\Pages\Admin\Users\Roles\RoleAddModal;
use Kanooh\Paddle\Pages\Admin\Users\Roles\RoleDeleteModal;
use Kanooh\Paddle\Pages\Admin\Users\Roles\RoleManagementTableRow;
use Kanooh\Paddle\Pages\Admin\Users\UsersPage;
use Kanooh\Paddle\Pages\Admin\Users\Roles\RolesPage;
use Kanooh\Paddle\Pages\Element\Links\LinkNotPresentException;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Role Management page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserRolesTest extends WebDriverTestCase
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
     * @var RolesPage
     */
    protected $rolesPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var UsersPage
     */
    protected $usersPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        // Create some instances to use later on.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->rolesPage = new RolesPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->usersPage = new UsersPage($this);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');

        // Initialize the App service.
        $this->appService = new AppService($this, $this->userSessionService);
    }

    /**
     * Tests if the link to the roles page is only visible when the paddlet is enabled.
     */
    public function testRoleLinkVisible()
    {
        // Disable the paddlet in case it is enabled.
        $app = new ProtectedContent;
        if (module_exists($app->getModuleName())) {
            $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
        }

        // Assert that the link is not visible anymore.
        $this->usersPage->go();
        try {
            $this->usersPage->rolesLink;
            $this->fail('The roles link should not be found.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Assert that the link is visible when the Protected Content paddlet is enabled.
        $this->appService->enableApp(new ProtectedContent);

        $this->usersPage->go();
        $this->assertNotNull($this->usersPage->rolesLink);
        $this->assertContains('admin/users/roles', $this->usersPage->rolesLink->attribute('href'));
    }

    /**
     * Tests if the Paddle roles are shown by default.
     */
    public function testPaddleRoles()
    {
        $this->appService->enableApp(new ProtectedContent);

        $this->rolesPage->go();

        // Assess that no exception is thrown because the row is found.
        $paddle_user_roles = array(
            'Chief Editor',
            'Editor',
            'Site Manager',
            'Read Only',
        );
        $paddle_hidden_user_roles = array(
            'anonymous user',
            'authenticated user',
        );

        foreach ($paddle_user_roles as $paddle_user_role) {
            $this->assertNotNull($this->rolesPage->table->getRoleManagementTableRowByRoleName($paddle_user_role));
        }

        // Make sure that the hidden roles are not found.
        foreach ($paddle_hidden_user_roles as $paddle_hidden_user_role) {
            $this->assertNull($this->rolesPage->table->getRoleManagementTableRowByRoleName($paddle_hidden_user_role));
        }
    }

    /**
     * Tests adding / editing / deleting roles.
     */
    public function testUserRole()
    {
        $this->appService->enableApp(new ProtectedContent);
        $paddle_roles = paddle_user_paddle_user_roles();

        $this->rolesPage->go();

        // Verify there are no edit and delete links for the paddle_roles.
        foreach ($paddle_roles as $rid => $paddle_role) {
            /** @var RoleManagementTableRow $row */
            $row = $this->rolesPage->table->getRoleManagementTableRowByRoleName($paddle_role);
            try {
                $row->actions->linkEdit;
                $this->fail('No edit link should be present for a paddle role.');
            } catch (LinkNotPresentException $e) {
                // Do nothing.
            }

            try {
                $row->actions->linkDelete;
                $this->fail('No delete link should be present for a paddle role.');
            } catch (LinkNotPresentException $e) {
                // Do nothing.
            }
        }

        $this->rolesPage->addRoleLink->click();

        // Add a new role.
        $role = $this->alphanumericTestDataProvider->getValidValue();
        $new_role = $this->alphanumericTestDataProvider->getValidValue();
        $updated_role = $this->alphanumericTestDataProvider->getValidValue();

        $modal = new RoleAddModal($this);
        $modal->waitUntilOpened();
        $modal->form->name->fill($role);
        $modal->submit();
        $modal->waitUntilClosed();

        // Assert that the new role is shown on the role list.
        $this->assertNotNull($this->rolesPage->table->getRoleManagementTableRowByRoleName($role));

        // Try to add the same role again and assert that you get an error message.
        $this->rolesPage->addRoleLink->click();
        $modal->waitUntilOpened();
        $modal->form->name->fill($role);
        $modal->submit();
        $this->assertTextPresent("The role name $role already exists. Choose another role name.");

        // Now create a new role.
        $modal->form->name->clear();
        $modal->form->name->fill($new_role);
        $modal->submit();
        $modal->waitUntilClosed();

        // Edit the role and give it a new name.
        /** @var RoleManagementTableRow $row */
        $row = $this->rolesPage->table->getRoleManagementTableRowByRoleName($new_role);
        $row->actions->linkEdit->click();
        $modal->waitUntilOpened();
        $this->assertEquals($new_role, $modal->form->name->getContent());

        // Try to set an existing role name.
        $modal->form->name->clear();
        $modal->form->name->fill($role);
        $modal->submit();
        $this->assertTextPresent("The role name $role already exists. Choose another role name.");

        // Try to set a new role name.
        $modal->form->name->clear();
        $modal->form->name->fill($updated_role);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->assertTextPresent('The role has been renamed.');

        // Assert that the old role name does no longer exist.
        $this->assertTextNotPresent($new_role);
        // Assert that the updated role name does exist.
        $this->assertTextPresent($updated_role);

        // Delete the new role.
        /** @var RoleManagementTableRow $row */
        $row = $this->rolesPage->table->getRoleManagementTableRowByRoleName($updated_role);
        $row->actions->linkDelete->click();
        $modal = new RoleDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->form->deleteButton->click();
        $modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Role deleted.');
        $this->assertTextNotPresent($updated_role);
    }
}
