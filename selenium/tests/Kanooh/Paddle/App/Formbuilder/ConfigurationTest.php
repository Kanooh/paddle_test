<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Formbuilder;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFormbuilder\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\FormbuilderViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Formbuilder paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var FormbuilderViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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
        $this->administrativeNodeViewPage = new FormbuilderViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Formbuilder);
    }

    /**
     * Tests the permissions table.
     */
    public function testPermissionsTable()
    {
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Verify that the needed permissions are being shown.
        $needed_permissions = array(
          'Access all webform results',
          'Access own webform results',
          'Edit all webform submissions',
          'Delete all webform submissions',
          'Access own webform submissions',
          'Edit own webform submissions',
          'Delete own webform submissions',
        );

        $this->configurePage->go();
        $rows = $this->configurePage->permissionsTable->rows;
        // This asserts to 8 because there is an extra row holding the "Webform"
        // title.
        $this->assertCount(8, $rows);

        foreach ($needed_permissions as $permission) {
            $this->assertTextPresent($permission);
        }

        $paddle_roles = paddle_user_paddle_user_roles();
        $editor_rid = array_search('Editor', $paddle_roles);

        // Check that the "access all webform results" permission is not
        // checked. Check that an editor cannot access the webform results of
        // a node he did not create.
        $checkbox = $rows[1]->getCheckbox($editor_rid . '[access all webform results]');
        $this->assertFalse($checkbox->isChecked());
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Check that an Editor cannot access the submissions and download for a
        // webform.
        $this->userSessionService->switchUser('Editor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Submissions', 'Download'));

        // Now give the Editor access rights to all webforms.
        $this->userSessionService->switchUser('SiteManager');
        $this->configurePage->go();
        $rows = $this->configurePage->permissionsTable->rows;
        $checkbox = $rows[1]->getCheckbox($editor_rid . '[access all webform results]');
        $checkbox->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Check that you can access the submissions and download for a webform.
        $this->userSessionService->switchUser('Editor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Submissions', 'Download'));

        // Revoke the access rights and give the editor only access to its own
        // webforms.
        $this->userSessionService->switchUser('SiteManager');
        $this->configurePage->go();
        $rows = $this->configurePage->permissionsTable->rows;
        $checkbox = $rows[1]->getCheckbox($editor_rid . '[access all webform results]');
        $this->assertTrue($checkbox->isChecked());
        $checkbox->uncheck();
        $checkbox = $rows[2]->getCheckbox($editor_rid . '[access own webform results]');
        $checkbox->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Check that an Editor cannot access the submissions and download for a
        // webform he did not create.
        $this->userSessionService->switchUser('Editor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Submissions', 'Download'));

        // Create a webform as Editor and verify that he can access the
        //submissions and download.
        $this->contentCreationService->createFormbuilderPageViaUI();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Submissions', 'Download'));

        // Verify that a chief editor cannot access the webform permissions.
        $this->userSessionService->switchUser('ChiefEditor');
        $this->configurePage->go();
        $this->assertTextPresent('You have insufficient access to manage webform permissions.');
    }
}
