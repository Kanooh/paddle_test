<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Comment\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Comment;

use Kanooh\Paddle\Apps\Comment;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Comment paddlet.
 *
 * @package Kanooh\Paddle\App\Comment
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

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
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');
        $this->appService->enableApp(new Comment);
    }

    /**
     * Tests the saving of the paddlet's default settings and the configuration.
     */
    public function testDefaultSettingsAndConfiguration()
    {
        // Make sure the site managers and (chief) editors can skip comment
        // approval since this is the only permission that can be changed from
        // the configuration.
        $role_names = array(
            'Editor',
            'Chief Editor',
            'Site Manager',
        );
        $this->assertSkipApprovalPermission($role_names);

        // Now check the configuration page.
        $this->configurePage->go();
        $this->assertTrue($this->configurePage->form->requireApproval->isSelected());

        // Now check the permission to skip approval.
        $this->configurePage->form->skipApproval->select();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $role_names = array(
            'anonymous user',
            'Read Only',
            'authenticated user',
            'Editor',
            'Chief Editor',
            'Site Manager',
        );
        $this->assertSkipApprovalPermission($role_names);

        // Reset the default settings.
        $this->configurePage->form->requireApproval->select();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Assert that the correct roles have the correct permission.
     */
    protected function assertSkipApprovalPermission($role_names)
    {
        drupal_static_reset('user_role_permissions');
        $roles = user_roles(false, 'skip comment approval');
        $this->assertEmpty(array_diff($roles, $role_names));
    }
}
