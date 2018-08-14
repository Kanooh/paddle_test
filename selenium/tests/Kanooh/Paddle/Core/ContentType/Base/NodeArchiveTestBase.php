<?php

/**
 * @file
 * Contains \Kanooh\Paddle\NodeArchiveTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Archive\ArchiveNodeModal;
use Kanooh\Paddle\Pages\Admin\Archive\RestoreNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonNotPresentException;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeArchiveTestBase extends WebDriverTestCase
{
    /**
     * @var AdminViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ArchivePage
     */
    protected $archivePage;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Indicates whether the content type can be deleted through the UI.
     *
     * @var bool
     */
    protected $contentTypeIsDeletable = true;

    /**
     * @var SearchPage
     */
    protected $contentManagerPage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var SiteSettingsPage
     */
    protected $siteSettingsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminNodeViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->archivePage = new ArchivePage($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->contentManagerPage = new SearchPage($this);
        $this->editPage = new EditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->siteSettingsPage = new SiteSettingsPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Data Provider for testNodeArchiving().
     */
    public function nodeArchiveDataProvider()
    {
        return array(
            array('Editor', array('Concept', 'To check')),
            array('ChiefEditor'),
        );
    }

    /**
     * Tests the archive status workflow.
     *
     * @group workflow
     * @group archive
     *
     * @dataProvider nodeArchiveDataProvider
     *
     * @param string $username
     *   The user name to run this test for.
     * @param array|null $allowed_states
     *   The states a user should be able to click the archive button. Null for all states.
     */
    public function testNodeArchiving($username, $allowed_states = null)
    {
        // Run the tests for all states for moderated nodes.
        $states = array(
            'Concept' => null,
            'To check' => array('buttonToEditor', 'buttonToAnyEditor'),
            'In review' => array('buttonToChiefEditor', 'buttonToAnyChiefEditor'),
            'Online' => 'buttonPublish',
            'Offline' => array('buttonPublish', 'buttonOffline'),
            'Scheduled for publication' => null,
            'Scheduled for depublication' => null,
        );

        // Prepare a node for each moderation test.
        // We do this now because we have to login as chief editor to be able
        // to perform all transitions.
        $this->userSessionService->login('ChiefEditor');

        $nodes = array();
        foreach ($states as $state => $moderate_button) {
            // Create a fresh test node.
            $nid = $this->setupNode();

            switch ($state) {
                case 'Scheduled for publication':
                    $this->contentCreationService->scheduleNodeForPublication($nid);
                    break;

                case 'Scheduled for depublication':
                    $this->contentCreationService->scheduleNodeForDepublication($nid);
                    break;
            }

            // Moderate the node to the new state.
            $this->adminNodeViewPage->go($nid);
            if (!empty($moderate_button)) {
                // Sometimes we have to click multiple buttons before
                // we get to the button that we actually want to click.
                // Example: Click the "To chief editor" button to reveal
                // the "Assign to x" button.
                if (is_array($moderate_button)) {
                    foreach ($moderate_button as $btn) {
                        $this->adminNodeViewPage->contextualToolbar->{$btn}->click();
                    }
                } else {
                    $this->adminNodeViewPage->contextualToolbar->{$moderate_button}->click();
                }
                $this->adminNodeViewPage->checkArrival();
            }

            // Verify that the node got the correct status.
            // This is mostly done to allow easier debugging on failures.
            $this->contentManagerPage->go();
            $row = $this->contentManagerPage->contentTable->getNodeRowByNid($nid);
            $this->assertEquals($state, $row->getStatus());

            // Save node id and status.
            $nodes[$nid] = $state;
        }

        // Now switch to the test user.
        $this->userSessionService->logout();
        $this->userSessionService->login($username);

        foreach ($nodes as $nid => $state) {
            $this->adminNodeViewPage->go($nid);
            if (empty($allowed_states) || in_array($state, $allowed_states)) {
                $this->adminNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Delete'));

                // Archive the node.
                $this->adminNodeViewPage->contextualToolbar->buttonArchive->click();
                $modal = new ArchiveNodeModal($this);
                $modal->waitUntilOpened();
                $modal->confirm();

                $this->adminNodeViewPage->checkArrival();
                if ($this->contentTypeIsDeletable) {
                    $this->assertTrue($this->adminNodeViewPage->contextualToolbar->buttonDelete->displayed());
                }
                $this->assertEquals(
                    'Archived',
                    $this->adminNodeViewPage->nodeSummary->getMetadata('workflow', 'status')['value'],
                    "Node not archived from state $state for user $username."
                );

                // Go to the Content manager overview page and make sure the
                // node is no longer displayed as it is now archived.
                $this->contentManagerPage->go();
                $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($nid));
            } else {
                try {
                    $this->adminNodeViewPage->contextualToolbar->buttonArchive->click();
                    $this->fail("User $username should not see archive button on a node with state $state.");
                } catch (ToolbarButtonNotPresentException $e) {
                    // The button is not present as expected.
                }
            }
        }
    }

    /**
     * Tests that a published node is not accessible anymore after being archived.
     *
     * @group workflow
     * @group archive
     */
    public function testArchivedNodeNotOnline()
    {
        $this->userSessionService->login('ChiefEditor');

        // Create a test node first and publish it.
        $nid = $this->setupNode();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Archive the node.
        $this->adminNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $modal->confirm();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend as anonymous and see that the node is not accessible.
        $this->assertNodeNotAccessibleWhenLoggedOut($nid);
    }

    /**
     * Tests that the scheduling settings are removed when archiving a node.
     */
    public function testArchiveScheduledNode()
    {
        $this->userSessionService->login('ChiefEditor');

        foreach (array(
                   'scheduleNodeForPublication',
                   'scheduleNodeForDepublication'
                 ) as $schedule) {
            // Create a fresh test node.
            $nid = $this->setupNode();
            $this->contentCreationService->{$schedule}($nid);

            $this->adminNodeViewPage->go($nid);
            $this->adminNodeViewPage->contextualToolbar->buttonArchive->click();

            $modal = new ArchiveNodeModal($this);
            $modal->waitUntilOpened();
            $modal->confirm();

            $this->adminNodeViewPage->checkArrival();

            $node = node_load($nid);
            $this->assertEmpty($node->publish_on);
            $this->assertEmpty($node->unpublish_on);
        }
    }

    /**
     * Test archiving on nodes with a published revision.
     *
     * @group archive
     */
    public function testPublishedNodesArchiving()
    {
        // Login as chief editor who can always archive nodes.
        $this->userSessionService->login('ChiefEditor');

        // Create and publish a node.
        $nid = $this->setupNode();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Check that the archive button is present for Chief editors.
        $this->adminNodeViewPage->contextualToolbar->checkButtons(array('Archive'));

        // Now logout and login as editor who cannot archive a node with
        // published revision.
        $this->userSessionService->switchUser('Editor');

        // The archive button should not be visible.
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Archive'));

        // Now create a revision of the node and check that the archive button
        // is not there.
        $this->editPage->go($nid);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Archive'));

        // Login as Chief editor again and make sure they still see the archive button.
        $this->userSessionService->switchUser('ChiefEditor');
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->checkButtons(array('Archive'));

        // Archive the node.
        $this->adminNodeViewPage->contextualToolbar->buttonArchive->click();

        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $modal->confirm();

        // Verify that the page is archived now.
        $this->adminNodeViewPage->checkArrival();
        $this->assertEquals(
            'Archived',
            $this->adminNodeViewPage->nodeSummary->getMetadata('workflow', 'status')['value']
        );

        // Go to the frontend as anonymous and see that the published revision
        // of the node is not published anymore.
        $this->assertNodeNotAccessibleWhenLoggedOut($nid);
    }

    /**
     * Tests the archive node overview.
     *
     * @group workflow
     * @group archive
     */
    public function testArchiveNodeOverview()
    {
        $this->userSessionService->login('ChiefEditor');

        // Create a fresh test node.
        $nid = $this->setupNode();

        // Verify it is not shown on the archive page.
        $this->archivePage->go();
        $this->assertFalse($this->archivePage->contentTable->getNodeRowByNid($nid));

        $this->contentCreationService->moderateNode($nid, 'archived');

        // Verify that after moderation to the archived state it is shown on the
        // archive page.
        $this->archivePage->go();
        /** @var ArchivePageContentTableRow|bool $row */
        $row = $this->archivePage->contentTable->getNodeRowByNid($nid);
        $this->assertNotFalse($row);
        $node = node_load($nid);
        $this->assertEquals($node->title, $row->title);
    }

    /**
     * Tests that our autocompletes do not suggest archived pages.
     *
     * @group workflow
     * @group archive
     */
    public function testAutocompletesExcludeArchivedPages()
    {
        // Delete all nodes to make sure in the autocomplete we get only the
        // archived node and the other one.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('node', false, array(), array('paddle_overview_page'));

        // Login as user who can edit Site Settings.
        $this->userSessionService->login('SiteManager');

        // Create a node to make sure at least 1 will appear in the autocomplete.
        $this->setupNode();

        // Create a archived test node.
        $node = array();
        $node['title'] = $this->alphanumericTestDataProvider->getValidValue();
        $node['nid'] = $this->setupNode($node['title']);
        $this->contentCreationService->moderateNode($node['nid'], 'archived');

        // Test that the archived node doesn't appear in the site settings
        // autocompletes.
        $this->siteSettingsPage->go();
        $this->siteSettingsPage->homePage->fill('node/');
        $this->assertAutocompleteNotContainsNode($node);

        // Test that the archived node doesn't appear in the Landing page
        // autocomplete. This one is used throughout the website. We will check
        // in the "Node content" pane.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_nid);

        // Add a node content pane to the basic page.
        $content_type = new NodeContentPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $node) {
                $content_type->getForm()->nodeContentAutocomplete->fill('node/');
                $webdriver->assertAutocompleteNotContainsNode($node);
            }
        );
        $region = $this->layoutPage->display->getRandomRegion();
        $region->addPane($content_type, $callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Test that the archived node doesn't appear in the autocomplete of the
        // menu items edit/create form.
        $this->menuOverviewPage->go();
        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();
        $modal->createMenuItemForm->title->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->createMenuItemForm->internalLinkPath->fill('node/');
        $this->assertAutocompleteNotContainsNode($node);
    }

    /**
     * Data provider for the restore node tests.
     */
    public function nodeRestoreUserDataProvider()
    {
        return array(
            array('ChiefEditor'),
            array('Editor'),
        );
    }

    /**
     * Tests the restore node links.
     *
     * This test covers the links related to the contextual toolbars and the
     * archive page table row actions.
     *
     * @group workflow
     * @group archive
     *
     * @dataProvider nodeRestoreUserDataProvider
     */
    public function testNodeRestoreFromArchive($username)
    {
        // Log in with the test user.
        $this->userSessionService->login($username);

        // Create a fresh test node.
        $nid = $this->setupNode();

        // Put it in archived status.
        $node = node_load($nid);
        workbench_moderation_moderate($node, 'archived');

        // Go to the archive page to find our node.
        $this->archivePage->go();
        $row = $this->archivePage->contentTable->getNodeRowByNid($nid);
        $this->assertNotFalse($row);

        // Restore the node with the row action links.
        $row->links->linkRestore->click();
        $modal = new RestoreNodeModal($this);
        $modal->waitUntilOpened();
        $modal->confirm();
        $this->archivePage->checkArrival();

        // Verify that the node is not in the archive anymore.
        $this->assertFalse($this->archivePage->contentTable->getNodeRowByNid($nid));

        // Go to the content manager page to search for our node.
        $this->contentManagerPage->go();
        $row = $this->contentManagerPage->contentTable->getNodeRowByNid($nid);
        $this->assertNotFalse($row);
        $this->assertEquals('Concept', $row->getStatus());

        // Moderate again the node.
        $node = node_load($nid);
        workbench_moderation_moderate($node, 'archived');

        // Go to the admin view of the node.
        $this->adminNodeViewPage->go($nid);

        // Restore the node through the contextual actions toolbar.
        $this->adminNodeViewPage->contextualToolbar->buttonRestore->click();
        $modal = new RestoreNodeModal($this);
        $modal->waitUntilOpened();
        $modal->confirm();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the node is not in the archive anymore.
        $this->assertEquals(
            'Concept',
            $this->adminNodeViewPage->nodeSummary->getMetadata('workflow', 'status')['value']
        );
    }

    /**
     * Tests the restore node bulk action.
     *
     * @group workflow
     * @group archive
     *
     * @dataProvider nodeRestoreUserDataProvider
     */
    public function testNodeRestoreBulkAction($username)
    {
        // Log in with the test user.
        $this->userSessionService->login($username);

        // Create two nodes.
        $nids = array($this->setupNode(), $this->setupNode());
        foreach ($nids as $nid) {
            $node = node_load($nid);
            workbench_moderation_moderate($node, 'archived');
        }

        // Go to the archive and select both the nodes.
        $this->archivePage->go();
        foreach ($nids as $nid) {
            $this->archivePage->contentTable->getNodeRowByNid($nid)->bulkActionCheckbox->check();
        }

        // Run the action.
        $this->archivePage->bulkActions->selectAction->selectOptionByLabel('Restore node from archive');
        $this->archivePage->bulkActions->executeButton->click();
        // Wait for the confirm page to appear.
        $this->waitUntilTextIsPresent('You selected the following 2 items');
        // Click the confirm button.
        $this->archivePage->bulkActions->buttonConfirm->click();
        // Wait for the page to be reloaded.
        $this->waitUntilTextIsPresent('Performed Restore node from archive on 2 items.');
        // Verify that the two nodes are not here anymore.
        foreach ($nids as $nid) {
            $this->assertFalse($this->archivePage->contentTable->getNodeRowByNid($nid));
        }

        // Go to the content manager and verify that the nodes are there in the
        // draft status.
        $this->contentManagerPage->go();
        foreach ($nids as $nid) {
            $row = $this->contentManagerPage->contentTable->getNodeRowByNid($nid);
            $this->assertNotFalse($row);
            $this->assertEquals('Concept', $row->getStatus());
        }
    }

    /**
     * Asserts that the passed node is not one of the suggestions of the
     * autocomplete that should be currently on the page.
     *
     * @param $node
     *   Array containing the title and the nid of the node we are expecting to
     *   be absent from the suggestions.
     */
    protected function assertAutocompleteNotContainsNode($node)
    {
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $this->assertNotContains($node['title'] . ' (node/' . $node['nid'] . ')', $suggestions);
        $autocomplete->pickSuggestionByPosition();
    }

    /**
     * Schedule a node for publication.
     *
     * @param int $nid
     *   The NID of the node.
     */
    protected function scheduleNodeForPublication($nid)
    {
        $this->editPage->go($nid);

        // We need to open the scheduler options first.
        $this->editPage->toggleSchedulerOptions();

        // The populateFields() is deprecated but we don't have any alternative
        // at the moment.
        $publish_on_ts = strtotime('+1 day');
        $this->editPage->publishOnDate->clear();
        $this->editPage->publishOnDate->value(date('d/m/Y', $publish_on_ts));
        $this->editPage->publishOnTime->clear();
        $this->editPage->publishOnTime->value(date('H:i:s', $publish_on_ts));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonSchedule->click();
    }

    /**
     * Schedule a node for depublication.
     *
     * @param int $nid
     *   The NID of the node.
     */
    protected function scheduleNodeForDepublication($nid)
    {
        $this->editPage->go($nid);

        // We need to open the scheduler options first.
        $this->editPage->toggleSchedulerOptions();

        // The populateFields() is deprecated but we don't have any alternative
        // at the moment.
        $unpublish_on_ts = strtotime('+2 days');
        $this->editPage->unpublishOnDate->clear();
        $this->editPage->unpublishOnDate->value(date('d/m/Y', $unpublish_on_ts));
        $this->editPage->unpublishOnTime->clear();
        $this->editPage->unpublishOnTime->value(date('H:i:s', $unpublish_on_ts));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
    }

    /**
     * Verifies that a node is not accessible when logged out.
     *
     * @param int $nid
     *   The NID of the node.
     */
    protected function assertNodeNotAccessibleWhenLoggedOut($nid)
    {
        $this->userSessionService->logout();

        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl("node/$nid");
        $response = $request->send();

        // Expect a 403.
        $this->assertEquals(403, $response->status);
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
