<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FacetedSearch\FacetedSearchTest.
 */

namespace Kanooh\Paddle\App\FacetedSearch;

use Kanooh\Paddle\Apps\FacetedSearch;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFacetedSearch\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Faceted Search Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FacetedSearchTest extends WebDriverTestCase
{
    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The configuration page of the faceted search paddlet.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * The Drupal Search API API.
     *
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * A list of nodes created for this test.
     *
     * @var array
     */
    protected $nodes = array();

    /**
     * The search page.
     *
     * @var PaddleSearchPage
     */
    protected $searchPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * The taxonomy terms created by this test.
     *
     * @var array
     */
    protected $taxonomyTerms = array();

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
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->editPage = new EditPage($this);
        $this->frontPage = new FrontPage($this);
        $this->searchPage = new PaddleSearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->taxonomyService = new TaxonomyService();

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
        $this->appService = new AppService($this, $this->userSessionService);
    }

    /**
     * Tests that the tags are searchable when the paddlet is enabled.
     *
     * @group search
     *
     * @see KANWEBS-3548
     */
    public function testSearchTagsAsFulltext()
    {
        // Always check that the app is disabled.
        $app = new FacetedSearch;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Use some prefixes to search for, as sometimes Solr doesn't like
        // totally random words.
        $general_term_prefix = 'general';
        $tag_term_prefix = 'tagged';

        // Create one term for each taxonomy.
        $general_term = "$general_term_prefix " . $this->alphanumericTestDataProvider->getValidValue();
        $tag_term = "$tag_term_prefix " . $this->alphanumericTestDataProvider->getValidValue();
        // Keep the term id of the general tags term as we need it for selection.
        $general_term_tid = $this->taxonomyService->createTerm(
            TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
            $general_term
        );
        // Mark term for deletion after test.
        $this->taxonomyTerms[] = $general_term_tid;

        // Create the tag term.
        $this->taxonomyTerms[] = $this->taxonomyService->createTerm(
            TaxonomyService::TAGS_VOCABULARY_ID,
            $tag_term
        );

        // Create one basic page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        // Mark node for deletion after test.
        $this->nodes[] = $nid;

        // Add the general term to the node.
        $this->editPage->go($nid);
        $this->editPage
            ->generalVocabularyTermReferenceTree
            ->getTermById($general_term_tid)
            ->select();

        // Add the tag to the node.
        $this->editPage->tags->value($tag_term);
        $this->editPage->tagsAddButton->click();
        $this->editPage->waitUntilTagIsDisplayed(ucfirst($tag_term));

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Publish the page.
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Assert that our page is found.
        $this->assertSearchResultByTerm($general_term_prefix, $title);
        $this->assertSearchResultByTerm($tag_term_prefix, $title);

        // Enable the app.
        $this->appService->enableApp(new FacetedSearch);

        // Clear the index so we are able to reindex it.
        $index = search_api_index_load('node_index');
        $index->clear();
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Reindex the node now.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Assert that our page is still there to be found.
        $this->assertSearchResultByTerm($general_term_prefix, $title);
        $this->assertSearchResultByTerm($tag_term_prefix, $title);
    }

    /**
     * Tests the hard limit on dynamically enabled facets.
     *
     * @group facets
     * @group search
     */
    public function testFacetsHardLimit()
    {
        $this->appService->enableApp(new FacetedSearch);

        // Create a root term.
        $root_tid = $this->taxonomyService->createTerm(
            TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
            $this->alphanumericTestDataProvider->getValidValue()
        );

        // Create 101 terms to surpass the facet limit count.
        $tids = array();
        for ($i = 0; $i < 101; $i++) {
            $tids[] = $this->taxonomyService->createTerm(
                TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
                $this->alphanumericTestDataProvider->getValidValue(),
                $root_tid
            );
        }

        // Mark all terms for deletion.
        $this->taxonomyTerms = $tids;
        $this->taxonomyTerms[] = $root_tid;

        // Enable the root term facet.
        $this->configurePage->go();
        $this->configurePage->facetTermsCheckboxes->getByValue($root_tid)->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create a basic page and tag it with all the terms.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Tag the node programmatically as it's faster and more reliable.
        $node = node_load($nid);

        /* @var \EntityMetadataWrapper $wrapper */
        $wrapper = entity_metadata_wrapper('node', $node);
        $wrapper->field_paddle_general_tags->set($tids);
        $wrapper->save();

        // Publish the node.
        $this->adminViewPage->go($nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the title.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($title);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Solr returns 100 terms, but the parent one is hidden as already
        // automatically expanded. Thus, the expected facet links count is 99.
        $this->assertCount(99, $this->searchPage->facets[$root_tid]->getInactiveLinks());
    }

    /**
     * Asserts that a search result is found searching by term title.
     *
     * @param string $term
     *   The title of the term the content was tagged with.
     * @param string $title
     *   The title of the content itself.
     */
    protected function assertSearchResultByTerm($term, $title)
    {
        // Search for the term.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($term);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Check that we have at least one result.
        $this->assertTrue($this->searchPage->searchResults->count() > 0);

        // Check that our page is there.
        $found = false;
        foreach ($this->searchPage->searchResults->getResults() as $result) {
            if ($result->title === $title) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all nodes created by this test.
        if (!empty($this->nodes)) {
            node_delete_multiple($this->nodes);
        }

        // Delete all the taxonomy terms created by this test.
        if (!empty($this->taxonomyTerms)) {
            foreach ($this->taxonomyTerms as $tid) {
                taxonomy_term_delete($tid);
            }
        }

        parent::tearDown();
    }
}
