<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\AdvancedSearch.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\AdvancedSearchLayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage as TaxonomyOverviewPage;
use Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\AdvancedSearchPage;
use Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\SearchPage\SearchResult;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test base for all the advanced search tests.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class AdvancedSearchTestBase extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
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
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var AdvancedSearchViewPage
     */
    protected $frontendViewPage;

    /**
     * The frontend node view for generic nodes.
     *
     * @var ViewPage
     */
    protected $generalFrontendViewPage;

    /**
     * The layout page for generic nodes.
     *
     * @var LayoutPage
     */
    protected $generalLayoutPage;

    /**
     * @var AdvancedSearchLayoutPage
     */
    protected $layoutPage;

    /**
     * @var AdvancedSearchPage
     */
    protected $nodeEditPage;

    /**
     * @var TaxonomyOverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

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
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->frontendViewPage = new AdvancedSearchViewPage($this);
        $this->generalFrontendViewPage = new ViewPage($this);
        $this->generalLayoutPage = new LayoutPage($this);
        $this->layoutPage = new AdvancedSearchLayoutPage($this);
        $this->nodeEditPage = new AdvancedSearchPage($this);
        $this->taxonomyOverviewPage = new TaxonomyOverviewPage($this);
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new AdvancedSearch);

        // Remove existing taxonomy terms.
        $this->cleanUpService->deleteEntities('taxonomy_term');
    }

    /**
     * Helper function to delete all the existing nodes.
     *
     * @throws \Exception
     */
    protected function deleteExistingNodes()
    {
        // Delete all the existing nodes. This has to be done because
        // without specifying any search options, all the nodes will be returned
        // by Solr. They will all have the same score, so they will be returned
        // with the same order they are stored in the index. This might lead
        // our nodes to not be in the first page.
        $this->cleanUpService->deleteEntities('node');
    }

    /**
     * Helper method to publish a node.
     *
     * @param int $nid
     *   The id of the node we want to publish.
     */
    protected function publishPage($nid)
    {
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Create nodes for a term structure.
     *
     * The structure is one provided by TaxonomyService::createHierarchicalStructure().
     * A single node will be created for every taxonomy term.
     *
     * @param array $structure
     *   The term structure.
     * @param string $prefix
     *   A string to use as prefix for the title.
     * @return array
     *   An array of node titles, keyed by taxonomy term id and node id.
     */
    protected function createNodeForTermStructure($structure, $prefix = '', $node_create_callback = null)
    {
        $titles = array();

        // We need to return the node titles used. array_walk_recursive()
        // doesn't allow to do that, so we use SPL iterators to recurse on array
        // leaves only.
        // @see http://stackoverflow.com/a/8590882
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($structure),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($iterator as $tid) {
            $title = $prefix . $this->alphanumericTestDataProvider->getValidValue();
            $nid = $this->createNodeForTerms($tid, $title, $node_create_callback);
            $titles[$tid][$nid] = $title;
        }

        return $titles;
    }

    /**
     * Create a new node for the specified term id.
     *
     * @param array|string $tids
     *   The taxonomy term ids to tag the node with.
     * @param string|null $title
     *   The title of the node. Leave empty to generate a random one.
     * @param null|callable $node_create_callback
     *   A callback to use for the node creation instead of the basic page default.
     *
     * @return int
     *   The nid of the node created.
     */
    protected function createNodeForTerms($tids, $title = null, $node_create_callback = null)
    {
        // Convert single value tids into array.
        if (!is_array($tids)) {
            $tids = array($tids);
        }

        if ($node_create_callback && is_callable($node_create_callback)) {
            $nid = call_user_func($node_create_callback, $title);
        } else {
            $nid = $this->contentCreationService->createBasicPage($title);
        }

        $node = node_load($nid);

        /* @ var \EntityMetadataWrapper $wrapper */
        $wrapper = entity_metadata_wrapper('node', $node);
        $wrapper->field_paddle_general_tags->set($tids);
        $wrapper->save();

        // Publish the node.
        $this->publishPage($nid);

        return $nid;
    }

    /**
     * Verify search result titles of a certain term structure.
     *
     * The structure is one provided by TaxonomyService::createHierarchicalStructure().
     *
     * @param array $structure
     *   The term structure.
     * @param array $titles
     *   The list of node titles, keyed by term id.
     * @param SearchResult[] $results
     *   The search results, keyed by title.
     */
    protected function assertResultsByTermStructure($structure, $titles, $results)
    {
        // Recurse only on leaves.
        // @see $this->createNodeForTermStructure()
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($structure),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $tid) {
            $this->assertArrayHasKey($tid, $titles, 'No nodes found.');
            foreach ($titles[$tid] as $nid => $title) {
                $this->assertArrayHasKey($title, $results);
            }
        }
    }

    /**
     * Verify a facet against the structure set up.
     *
     * @param \Kanooh\Paddle\Pages\Element\Search\Facet $facet
     *   The facet to verify.
     * @param array $expected_inactive
     *   The expected inactive links, with term ids as keys and item count as value.
     * @param array $expected_active
     *   The expected active links.
     */
    protected function assertFacetLinks($facet, $expected_inactive, $expected_active)
    {
        $inactive_links = $facet->getInactiveLinks();

        // Assert the correct number of inactive links shown.
        $this->assertSameSize(array_filter($expected_inactive), $inactive_links);

        // Assert that the values and item count of the inactive links match.
        foreach ($inactive_links as $facet_link) {
            $this->assertArrayHasKey($facet_link->value, $expected_inactive);
            $this->assertEquals($expected_inactive[$facet_link->value], $facet_link->itemCount);
        }

        $active_links = $facet->getActiveLinks();

        // Assert the correct number of facet links shown.
        $this->assertSameSize(array_filter($expected_active), $active_links);

        // Assert that the values of the active links match.
        foreach ($active_links as $facet_link) {
            $this->assertArrayHasKey($facet_link->value, $expected_active);
            $this->assertEquals($expected_active[$facet_link->value], $facet_link->itemCount);
        }
    }
}
