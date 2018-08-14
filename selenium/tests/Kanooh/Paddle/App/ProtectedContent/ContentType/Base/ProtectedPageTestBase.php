<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ProtectedContent\ContentType\Base\ProtectedPageTestBase.
 */

namespace Kanooh\Paddle\App\ProtectedContent\ContentType\Base;

use Kanooh\Paddle\Apps\ProtectedContent;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserRoleService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests for protected content on content types.
 *
 * @package Kanooh\Paddle\App\ProtectedContent\ContentType\Base
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class ProtectedPageTestBase extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

    /**
     * @var UserRoleService
     */
    protected $userRoleService;

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
        $this->cleanUpService = new CleanUpService($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userRoleService = new UserRoleService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new ProtectedContent);
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract protected function setupNode($title = null);

    /**
     * Tests the the paddlet's default and extra settings.
     *
     * @group ProtectedPageTestBase
     * @group ProtectedContent
     */
    public function testDefaultAndExtraSettings()
    {
        // Prerequisite: ensure there are no leftover custom roles from other
        // test runs.
        $this->cleanUpService->deleteCustomUserRoles();

        // Log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');

        // Create 2 roles.
        // There's currently no user interface for this yet, so we create them
        // programmatically. Even when the interface arrives, it's not the job
        // of this test to test the user interface for creating user roles.
        $this->userRoleService->createUserRole('Democrats');
        $this->userRoleService->createUserRole('Republicans');

        // Create a page.
        $nid = $this->setupNode();

        // Go to the node edit page.
        $this->nodeEditPage->go($nid);

        // Verify that you see a permissions pane on the screen.
        // Verify "Everyone" is checked by default.
        $this->assertTrue($this->nodeEditPage->protectedPageRadioButtons->everyone->isSelected());

        // Verify "Only logged in users, but all of them" is also available.
        $this->nodeEditPage->protectedPageRadioButtons->authenticated->select();

        // Verify the role selection is still not visible.
        $this->assertFalse($this->nodeEditPage->protectedPageUserRolesCheckBoxes->isDisplayed());

        // Verify the role selection shows after selecting "Only specific logged in user roles".
        $this->nodeEditPage->protectedPageRadioButtons->specific_roles->select();

        // Verify it only shows the custom roles.
        $this->assertEquals(2, $this->nodeEditPage->protectedPageUserRolesCheckBoxes->count());
        foreach (array_keys(paddle_protected_content_custom_user_roles()) as $role_id) {
            $this->assertNotFalse($this->nodeEditPage->protectedPageUserRolesCheckBoxes->getByValue($role_id));
        }
    }
}
