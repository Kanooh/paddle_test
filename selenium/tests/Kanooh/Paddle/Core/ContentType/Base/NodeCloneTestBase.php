<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeCloneTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Modal\CloneNodeModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test for the node clone functionality.
 */
abstract class NodeCloneTestBase extends WebDriverTestCase
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
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * @var SearchPage
     */
    protected $searchPage;

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

        // Instantiate the Pages that will be visited in the test.
        $this->cleanUpService = new CleanUpService($this);
        $this->searchPage = new SearchPage($this);
        $this->viewPage = new ViewPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        $drupalService = new DrupalService();
        $drupalService->bootstrap($this);
    }

    /**
     * Test if the "Clone" button works on the content overview page.
     *
     * @group contentType
     * @group nodeCloneTestBase
     */
    public function testCloneNode()
    {
        $this->cleanUpService->deleteEntities('node', false, array(), array('paddle_overview_page'));

        // Setup the node and go to content manager overview.
        $nid = $this->setupNode();
        $node = node_load($nid);

        $this->searchPage->go();

        /** @var SearchPageContentTableRow $row */
        $row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $row->links->linkClone->click();
        $this->waitUntilTextIsPresent('Are you sure you want to clone');

        $modal = new CloneNodeModal($this);
        $modal->waitUntilOpened();
        $modal->buttonCancel->click();
        $modal->waitUntilClosed();
        $this->searchPage->checkArrival();

        $states = workbench_moderation_states();

        // Remove the archived state as the node will not appear on the Content
        // Manager page and it cannot be cloned in that state.
        unset($states['archived']);
        foreach ($states as $state) {
            workbench_moderation_moderate($node, $state->name);

            $row = $this->searchPage->contentTable->getNodeRowByNid($nid);
            $row->links->linkClone->click();
            $this->waitUntilTextIsPresent('Are you sure you want to clone');

            $modal = new CloneNodeModal($this);
            $modal->buttonConfirm->click();
            $this->viewPage->checkArrival();
            $clone_nid = $this->viewPage->getNodeIDFromUrl();
            $this->assertEquals('Concept', $this->viewPage->nodeSummary->getMetadata('workflow', 'status')['value']);

            // Now delete the clone to make sure the original node is first in
            // the table. We need this because Selenium has difficulty clicking
            // on the clone button when the original node goes down in the table.
            node_delete($clone_nid);

            $this->searchPage->go();
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
