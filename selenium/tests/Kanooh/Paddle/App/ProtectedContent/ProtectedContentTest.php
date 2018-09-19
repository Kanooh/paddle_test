<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ProtectedContent\ProtectedContentTest.
 */

namespace Kanooh\Paddle\App\ProtectedContent;

use Kanooh\Paddle\Apps\ProtectedContent;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Pages\PaddlePageWrongPathException;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\UserRoleService;
use Kanooh\Paddle\Utilities\UserService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class in which we test the functionality of the Protected Content paddlet.
 *
 * @package Kanooh\Paddle\App\ProtectedContent
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ProtectedContentTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

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
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

    /**
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

    /**
     * @var PaddleSearchPage
     */
    protected $searchPage;

    /**
     * @var UserRoleService
     */
    protected $userRoleService;

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
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->frontendNodeViewPage = new FrontEndView($this);
        $this->nodeEditPage = new EditPage($this);
        $this->searchPage = new PaddleSearchPage($this);
        $this->userRoleService = new UserRoleService($this);
        $this->userService = new UserService($this);
        $this->userSessionService = new UserSessionService($this);

        $this->userSessionService->login('ChiefEditor');

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->appService = new AppService($this, $this->userSessionService);
    }

    /**
     * Tests that published nodes are viewable for all by installation.
     *
     * We need to test if nodes published before the paddlet has been installed
     * will not have their view permissions changed JUST by installing this paddlet.
     *
     * @group ProtectedContent
     */
    public function testDefaultPermissionsOnExistingPages()
    {
        // Disable the App to restore the node_access table to its default state.
        $app = new ProtectedContent;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Create a basic page and publish it.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Enable the paddlet.
        $this->appService->enableApp($app);

        $this->nodeEditPage->go($nid);
        // Verify "Everyone" is checked by default.
        $this->assertTrue($this->nodeEditPage->protectedPageRadioButtons->everyone->isSelected());

        // Click on the "Back" button, do not save the node.
        $this->nodeEditPage->contextualToolbar->buttonBack->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Log out.
        $this->userSessionService->logout();

        // Go to the front-end of the basic page and assert that you can still view it
        // as anonymous user.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($title);
    }

    /**
     * Tests the protected content options.
     *
     * @group ProtectedContent
     */
    public function testProtectedAccess()
    {
        // Enable the app if it is not yet enabled.
        $this->appService->enableApp(new ProtectedContent);

        // Prerequisite: ensure there are no leftover custom roles from other
        // test runs.
        $this->cleanUpService->deleteCustomUserRoles();
        $this->cleanUpService->deleteCustomUsers();

        // Create an external user role and assign it to an external user.
        // Make sure the user has the authenticated user role as well.
        $this->userRoleService->createUserRole('external');
        $user = $this->userService->createUser(array('name' => 'external user'));
        $this->userService->assignRolesToUser($user, array('authenticated user', 'external'));

        // Create a new basic page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Go to the node edit page.
        $this->nodeEditPage->go($nid);

        // Select the  "Only specific logged in user roles" option.
        $this->nodeEditPage->protectedPageRadioButtons->specific_roles->select();

        // Get the rid of your user role.
        $rid = array_search('external', user_roles(true));

        // Check the user role.
        $this->nodeEditPage->protectedPageUserRolesCheckBoxes->getByValue($rid)->check();

        // Save and publish your page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Log in as External user.
        $this->userSessionService->logout();
        $this->userSessionService->customLogin('external user');

        // Assert that you can view the front-end of the basic page.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($title);

        // Log out and assert that you cannot view the page.
        $this->userSessionService->logout();

        try {
            $this->frontendNodeViewPage->go($nid);
            $this->fail('You should not be able to browse to the page.');
        } catch (PaddlePageWrongPathException $e) {
            // Do nothing.
        }
    }

    /**
     * Tests the node access on the search page.
     */
    public function testNodeAccessOnSearch()
    {
        // Enable the app if it is not yet enabled.
        $this->appService->enableApp(new ProtectedContent);

        // Prerequisite: ensure there are no leftover custom roles from other
        // test runs.
        $this->cleanUpService->deleteCustomUserRoles();
        $this->cleanUpService->deleteCustomUsers();
        $this->cleanUpService->deleteEntities('node');

        // Create 2 custom user3 role and assign it to a new user each.
        // Make sure the user has the authenticated user role as well.
        $this->userRoleService->createUserRole('external');
        $user = $this->userService->createUser(array('name' => 'external user'));
        $this->userService->assignRolesToUser($user, array('authenticated user', 'external'));

        $this->userRoleService->createUserRole('internal');
        $user = $this->userService->createUser(array('name' => 'internal user'));
        $this->userService->assignRolesToUser($user, array('authenticated user', 'internal'));

        // Create 3 nodes. 1 with access for all logged in users, 1 with access
        // for a certain role, 1 with access for everyone.
        $access_all_title = 'test' . $this->alphanumericTestDataProvider->getValidValue();
        $access_external_title = 'test' . $this->alphanumericTestDataProvider->getValidValue();
        $access_logged_in_title = 'test' . $this->alphanumericTestDataProvider->getValidValue();

        $nodes = array(
            'access_all' => $this->contentCreationService->createBasicPage($access_all_title),
            'access_external' => $this->contentCreationService->createBasicPage($access_external_title),
            'access_logged_in' => $this->contentCreationService->createBasicPage($access_logged_in_title),
        );

        foreach ($nodes as $access => $nid) {
            $this->nodeEditPage->go($nid);

            switch ($access) {
                case 'access_all':
                    // Select the  "access all" option.
                    $this->nodeEditPage->protectedPageRadioButtons->everyone->select();
                    break;
                case 'access_external':
                    // Select the  "Only specific logged in user roles" option.
                    $this->nodeEditPage->protectedPageRadioButtons->specific_roles->select();

                    // Get the rid of your user role.
                    $rid = array_search('external', user_roles(true));

                    // Check the user role.
                    $this->nodeEditPage->protectedPageUserRolesCheckBoxes->getByValue($rid)->check();
                    break;
                case 'access_logged_in':
                    // Select the  "authenticated user roles" option.
                    $this->nodeEditPage->protectedPageRadioButtons->authenticated->select();
                    break;
            }

            // Save and publish your page.
            $this->nodeEditPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the search page
        $this->searchPage->go();

        // Search as paddle user and verify you see 3 nodes.
        $this->searchPage->form->keywords->fill('test');
        $this->searchPage->form->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have the expected nodes count.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(3, $results);

        // Log in as External user and verify you get 3 nodes when searching.
        $this->userSessionService->logout();
        $this->userSessionService->customLogin('external user');

        // Go to the search page
        $this->searchPage->go();

        // Search as paddle user and verify you see 3 nodes.
        $this->searchPage->form->keywords->fill('test');
        $this->searchPage->form->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have the expected nodes count.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(3, $results);

        // Log in as Internal user and verify you get 2 nodes when searching.
        $this->userSessionService->logout();
        $this->userSessionService->customLogin('internal user');

        // Go to the search page
        $this->searchPage->go();

        // Search as paddle user and verify you see 3 nodes.
        $this->searchPage->form->keywords->fill('test');
        $this->searchPage->form->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have the expected nodes count.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(2, $results);

        // Test as anonymous user and verify you get 1 node when searching.
        $this->userSessionService->logout();

        // Go to the search page
        $this->searchPage->go();

        // Search as paddle user and verify you see 3 nodes.
        $this->searchPage->form->keywords->fill('test');
        $this->searchPage->form->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have the expected nodes count.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(1, $results);
    }

    /**
     * Tests that anonymous users cannot view unpublished pages.
     */
    public function testAnonymousCantViewNonPublishedPages()
    {
        // Enable the app if it is not yet enabled.
        $this->appService->enableApp(new ProtectedContent);
        $nid = $this->contentCreationService->createBasicPage();

        $this->userSessionService->logout();

        try {
            $this->frontendNodeViewPage->go($nid);
            $this->fail('Anonymous users should not be able to visit unpublished content.');
        } catch (\Exception $e) {
            // Do nothing.
        }
    }

      /**
       * Tests that the Page visibility able and value are not visible on frontend
       * @see KANWEBS-5750.
       */
    public function testPageVisibilityTextNotVisible()
    {
        // Enable the app if it is not yet enabled.
        $this->appService->enableApp(new ProtectedContent);
        $this->appService->enableApp(new Poll);
        $nid = $this->contentCreationService->createPollPageViaUI();
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextNotPresent('Page visibility');
        $this->assertTextNotPresent('Everyone');
    }
}
