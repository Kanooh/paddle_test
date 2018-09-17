<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage as ContentRegionLayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the content discovery tabs.
 */
abstract class ContentDiscoveryTestBase extends WebDriverTestCase
{

    /**
     * @var AddPage
     */
    protected $addContentPage;

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
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var SearchPage
     */
    protected $searchPage;

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

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->searchPage = new SearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Get the 'Page layout' page belonging to a certain node type.
     *
     * @return ContentRegionLayoutPage
     */
    protected function getLayoutPage()
    {
        return new ContentRegionLayoutPage($this);
    }

    /**
     * Data Provider for testContentDiscoveryActions().
     */
    public function userDataProvider()
    {
        return array(
            array('Editor'),
            array('ChiefEditor'),
        );
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract protected function setupNode();

    /**
     * Schedule a node for publication / unpublication.
     *
     * @param int $nid
     *   The NID of the node.
     */
    public function scheduleNode($nid)
    {
        $this->nodeEditPage->go($nid);
        $publish_on_ts = strtotime('+1 day');
        $unpublish_on_ts = strtotime('+2 days');

        // We need to open the scheduler options first.
        $this->nodeEditPage->toggleSchedulerOptions();

        // The populateFields() is deprecated but we don't have any alternative
        // at the moment.
        $this->nodeEditPage->populateFields(
            array(
                'publish_on[date]' => date('d/m/Y', $publish_on_ts),
                'publish_on[time]' => date('H:i:s', $publish_on_ts),
                'unpublish_on[date]' => date('d/m/Y', $unpublish_on_ts),
                'unpublish_on[time]' => date('H:i:s', $unpublish_on_ts),
            )
        );
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonSchedule->click();
    }

    /**
     * Tests the actions on the content discovery tests.
     *
     * @dataProvider userDataProvider
     * @group contentDiscoveryTestBase
     * @group contentType
     * @group workflow
     */
    public function testContentDiscoveryActions($user)
    {
        $this->prepareSession()->currentWindow()->maximize();

        // Log in as the desired user.
        $this->userSessionService->login($user);
        // Cleanup existing nodes so the searchpage can find the node we'll
        // create.
        $this->cleanUpService->deleteEntities('node', false, array(), array('paddle_overview_page'));

        // Create a node.
        $nid = $this->setupNode();

        // Go to the content discovery tabs and verify that the actions are displayed
        // on all discovery tabs.
        $this->searchPage->go();
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->checkLinks(array('PageProperties', 'PageLayout', 'FrontView', 'AdminView'));

        // Click all actions and verify they have the wanted behaviour.
        // Click the front end view link.
        $node_row->links->linkFrontView->click();
        $this->frontEndNodeViewPage->checkArrival();
        $this->searchPage->go();

        // Click the backend view link.
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->linkAdminView->click();
        $this->adminNodeViewPage->checkArrival();
        $this->searchPage->go();

        // Click the page properties link.
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->linkPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->searchPage->go();

        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->linkPageLayout->click();
        $layout_page = $this->getLayoutPage();
        $layout_page->checkArrival();
        $layout_page->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Run the tests for all states for moderated nodes.
        $states = array(
            'Concept' => null,
            'To check' => array('buttonToEditor', 'buttonToAnyEditor'),
            'In review' => array('buttonToChiefEditor', 'buttonToAnyChiefEditor'),
            'Online' => 'buttonPublish',
            'Offline' => 'buttonOffline',
            'Scheduled for publication' => null,
            'Scheduled for depublication' => null,
        );

        foreach ($states as $state => $moderate_button) {
            $test_case = $this;

            // Schedule the node to be published on another date. Always use the
            // Chief Editor user for this, because a regular user is not
            // allowed to finalize the scheduling.
            if ($state == 'Scheduled for publication') {
                $callable = new SerializableClosure(
                    function () use ($test_case, $nid) {
                        $test_case->scheduleNode($nid);
                    }
                );
                $this->userSessionService->runAsUser('ChiefEditor', $callable);
            }

            // Moderate the node to the new state. Always use the Chief Editor
            // user for this, because a regular user is not allowed to perform
            // certain state transitions.
            if (!empty($moderate_button)) {
                $callable = new SerializableClosure(
                    function () use ($test_case, $nid, $moderate_button) {
                        $test_case->adminNodeViewPage->go($nid);
                        // Sometimes we have to click multiple buttons before
                        // we get to the button that we actually want to click.
                        // Example: Click the "To chief editor" button to reveal
                        // the "Assign to x" button.
                        if (is_array($moderate_button)) {
                            foreach ($moderate_button as $btn) {
                                $test_case->adminNodeViewPage->contextualToolbar->{$btn}->click();
                            }
                        } else {
                            $test_case->adminNodeViewPage->contextualToolbar->{$moderate_button}->click();
                        }
                        $test_case->adminNodeViewPage->checkArrival();
                    }
                );
                $this->userSessionService->runAsUser('ChiefEditor', $callable);
            }

            // Go to the content discovery.
            $this->searchPage->go();
            $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);

            // If the editor is supposed to be able to edit the node in its
            // current state or not.
            if ('Editor' == $user) {
                $can_edit_node = !in_array($state, array('In review'));
            } else {
                $can_edit_node = true;
            }

            try {
                if ($can_edit_node) {
                    $node_row->links->checkLinks(array('PageProperties', 'PageLayout', 'FrontView', 'AdminView'));
                } else {
                    $node_row->links->checkLinks(array('FrontView', 'AdminView'));
                    $node_row->links->checkLinksNotPresent(array('PageProperties', 'PageLayout'));
                }
            } catch (\Exception $e) {
                $this->fail("Content discovery, user $user" . $e->getMessage());
            }

            // Click all actions and verify they have the wanted behaviour.
            // Click the front end view link.
            $node_row->links->linkFrontView->click();
            $this->frontEndNodeViewPage->checkArrival();
            $this->searchPage->go();

            // Click the backend view link.
            $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
            $node_row->links->linkAdminView->click();
            $this->adminNodeViewPage->checkArrival();
            $this->searchPage->go();

            if ($can_edit_node) {
                // Click the page properties link.
                $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
                $node_row->links->linkPageProperties->click();
                $this->nodeEditPage->checkArrival();
                $this->nodeEditPage->contextualToolbar->buttonSave->click();
                $this->adminNodeViewPage->checkArrival();
                $this->searchPage->go();

                // Click the page layout link.
                $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
                $node_row->links->linkPageLayout->click();
                $layout_page = $this->getLayoutPage();
                $layout_page->checkArrival();
                $layout_page->contextualToolbar->buttonSave->click();
                $this->adminNodeViewPage->checkArrival();
            }
        }
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
