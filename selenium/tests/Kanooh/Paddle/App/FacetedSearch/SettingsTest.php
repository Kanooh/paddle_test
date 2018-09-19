<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FacetedSearch\SettingsTest.
 */

namespace Kanooh\Paddle\App\FacetedSearch;

use Kanooh\Paddle\Apps\FacetedSearch;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFacetedSearch\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage as TaxonomyOverviewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageCreateTermModal;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the configuration form of the Faceted Search app.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SettingsTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * The configuration page of the faceted search paddlet.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * The content creation service.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The Drupal Search API API.
     *
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

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
     * The search page.
     *
     * @var PaddleSearchPage
     */
    protected $contentPage;

    /**
     * The taxonomy overview page.
     *
     * @var TaxonomyOverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * Taxonomy service.
     *
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
     * User session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->frontPage = new FrontPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->searchPage = new PaddleSearchPage($this);
        $this->taxonomyOverviewPage = new TaxonomyOverviewPage($this);
        $this->contentPage = new SearchPage($this);

        $this->taxonomyService = new TaxonomyService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Enable the faceted search app.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new FacetedSearch);
    }

    /**
     * Tests the configuration page.
     *
     * @group facets
     * @group search
     * @group store
     */
    public function testConfigurationPage()
    {
        // Go to the configuration page and check if the checkboxes for the
        // node types are there.
        $this->configurePage->go();

        // Check a checkbox and save. Verify it has been saved.
        $this->configurePage->contentTypesCheckboxes->getByValue('basic_page')->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');
        $this->assertTrue($this->configurePage->contentTypesCheckboxes->getByValue('basic_page')->isChecked());

        // Create a basic page and landing page that uses a 24-character title
        // to lower the chance of generating a title that's already used in
        // another test.
        $title = $this->alphanumericTestDataProvider->getValidValue(24);
        $basic_page_nid = $this->contentCreationService->createBasicPage($title);
        $landing_page_nid = $this->contentCreationService->createLandingPage(null, $title);

        // Publish the pages.
        $this->administrativeNodeViewPage->go($basic_page_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->waitUntilPageIsLoaded();
        $this->administrativeNodeViewPage->go($landing_page_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->waitUntilPageIsLoaded();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the title.
        $this->searchPage->go();
        $this->searchPage->searchForm->keywords->fill($title);
        $this->searchPage->searchForm->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Check that we have 1 result.
        $this->assertEquals(1, $this->searchPage->searchResults->count());

        // Go to the configuration page.
        $this->configurePage->go();

        // Uncheck the set checkbox and save. Verify it has been saved.
        $this->configurePage->contentTypesCheckboxes->getByValue('basic_page')->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');
        $this->assertFalse($this->configurePage->contentTypesCheckboxes->getByValue('basic_page')->isChecked());

        // Search for the title.
        $this->searchPage->go();
        $this->searchPage->searchForm->keywords->fill($title);
        $this->searchPage->searchForm->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Check that we have 2 results.
        $this->assertEquals(2, $this->searchPage->searchResults->count());

        // Verify that the results are correct.
        foreach ($this->searchPage->searchResults->getResults() as $result) {
            $this->assertEquals($title, $result->title);
        }

        // Check that the result title is correct.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertEquals($title, $results[0]->title);

        // Prepare a multi-level taxonomy in the general taxonomy with two
        // root terms.
        $vocabulary = taxonomy_vocabulary_machine_name_load('paddle_general');
        $prefix = $this->alphanumericTestDataProvider->getValidValue(4) . ' ';
        $this->taxonomyTerms = $this->taxonomyService->createHierarchicalStructure($vocabulary->vid, 2, 2, 0, $prefix);

        // Load all the first level terms on the general taxonomy.
        // We cannot trust to have only the two root elements created before
        // as not all the tests clear the test data on tear down.
        $root_terms = taxonomy_get_tree($vocabulary->vid, 0, 1);

        // Go to the configuration page.
        $this->configurePage->go();

        // Verify that the number of facet terms checkboxes present is the same
        // as the root terms found.
        $this->assertEquals(count($root_terms), $this->configurePage->facetTermsCheckboxes->count());

        // Check the first root term, save the page and verify that the settings
        // are kept.
        $this->configurePage->facetTermsCheckboxes->getByValue($this->taxonomyTerms[1]['#tid'])->check();
        $this->configurePage->save();
        $this->assertTrue(
            $this->configurePage->facetTermsCheckboxes
                ->getByValue($this->taxonomyTerms[1]['#tid'])
                ->isChecked()
        );
        $this->assertFalse(
            $this->configurePage->facetTermsCheckboxes
                ->getByValue($this->taxonomyTerms[2]['#tid'])
                ->isChecked()
        );

        // Add a new root term.
        $new_term_tid = $this->taxonomyService->createTerm(
            $vocabulary->vid,
            $this->alphanumericTestDataProvider->getValidValue()
        );

        // Add the term to delete list.
        $this->taxonomyTerms[] = array('#tid' => $new_term_tid);

        // Refresh the page and verify that the new term is present and
        // unselected by default.
        $this->configurePage->reloadPage();
        $new_checkbox = $this->configurePage->facetTermsCheckboxes->getByValue($new_term_tid);
        $this->assertNotNull($new_checkbox, 'The newly created term is present.');
        $this->assertFalse($new_checkbox->isChecked());
    }

    /**
     * Tests the frontend facet search.
     *
     * @group facets
     * @group search
     */
    public function testFacets()
    {
        // Save the current status about the node index.
        $before_status = $this->drupalSearchApiApi->getStatus('node_index');

        // Keep the vocabulary id for later usage.
        $vid = taxonomy_vocabulary_machine_name_load('paddle_general')->vid;

        // Prepare prefix for the multi-level taxonomy.
        $prefix = $this->alphanumericTestDataProvider->getValidValue(4) . ' ';

        // Create 3 root terms.
        $this->taxonomyTerms = $this->taxonomyService->createHierarchicalStructure($vid, 1, 3, 0, $prefix);

        // Make the first term have 2 levels with 2 terms each underneath.
        $this->taxonomyTerms[1] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 2, $this->taxonomyTerms[1]['#tid'], "{$prefix}1-");

        // Second term has 2 flat children.
        $this->taxonomyTerms[2] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 1, 2, $this->taxonomyTerms[2]['#tid'], "{$prefix}2-");

        // Third term has 2 levels with 1 term each.
        $this->taxonomyTerms[3] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 1, $this->taxonomyTerms[3]['#tid'], "{$prefix}3-");

        // Prepare a prefix to be used for page titles. This will help us to
        // have search results.
        $title_prefix = 'Klaatu ';

        // Create one node for each level.
        array_walk_recursive($this->taxonomyTerms, array($this, 'createNodeForTerms'), $title_prefix);

        // Add a node that is tagged with two terms.
        $nid = $this->createNodeForTerms(array(
            $this->taxonomyTerms[1][2]['#tid'],
            $this->taxonomyTerms[3][1][1]['#tid'],
        ), 0, $title_prefix);

        // Add this node to the nids list.
        $this->nodes[] = $nid;

        // Load the title to use it later on multiple facets search.
        $node = node_load($nid);
        $multiple_terms_node_title = $node->title;

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Fetch the new index status and check that the nodes have been
        // indexed.
        $after_status = $this->drupalSearchApiApi->getStatus('node_index');
        // Assert that we have 14 more nodes indexed.
        $this->assertEquals($before_status['total'] + 14, $after_status['total']);
        // Assert that all the nodes have been indexed.
        $this->assertEquals($after_status['total'], $after_status['indexed']);

        // Select the first and third term to be shown as facets.
        // Deselect all the others. Use tids as key so it's easier to do an
        // isset instead of array_search().
        $configured_facet_tids = array(
            $this->taxonomyTerms[1]['#tid'] => true,
            $this->taxonomyTerms[3]['#tid'] => true,
        );
        $this->configurePage->go();
        foreach ($this->configurePage->facetTermsCheckboxes->getAll() as $value => $checkbox) {
            isset($configured_facet_tids[$value]) ? $checkbox->check() : $checkbox->uncheck();
        }
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();

        // Now search the pages created, using the title prefix.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($title_prefix);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // We should find all the node created:
        // - 1 + (1 * 2) + (2 * 2) on the first level
        // - 1 + 2 on the second level
        // - 1 + 1 + 1 on third level
        // - 1 node on first and third level
        $this->assertEquals(14, $this->searchPage->searchResults->count());

        // Assert that we have 2 facets shown.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);

        // Assert that the facets shown are the configured terms.
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Verify the first facet.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[1]['#tid']],
            array(
                $this->taxonomyTerms[1][1]['#tid'] => 3,
                $this->taxonomyTerms[1][2]['#tid'] => 4,
            ),
            array()
        );

        // Verify the other facet (which is based on the third term).
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[3]['#tid']],
            array(
                $this->taxonomyTerms[3][1]['#tid'] => 3,
            ),
            array()
        );

        // Click on the first link and verify the new status.
        $facets[$this->taxonomyTerms[1]['#tid']]->getInactiveLinkByValue($this->taxonomyTerms[1][1]['#tid'])->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert the correct number of results.
        $this->assertEquals(3, $this->searchPage->searchResults->count());

        // Assert that we have only one facet now.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array_keys($facets), array(
            $this->taxonomyTerms[1]['#tid'],
        ));

        // Fetch the first facet again, and verify that it's showing the next
        // two values.
        $facet = $facets[$this->taxonomyTerms[1]['#tid']];
        $this->assertFacetLinks(
            $facet,
            array(
                $this->taxonomyTerms[1][1][1]['#tid'] => 1,
                $this->taxonomyTerms[1][1][2]['#tid'] => 1,
            ),
            array(
                $this->taxonomyTerms[1][1]['#tid'] => true,
            )
        );

        // Click another link and verify the new status.
        $facet->getInactiveLinkByValue($this->taxonomyTerms[1][1][2]['#tid'])->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert the correct number of results.
        $this->assertEquals(1, $this->searchPage->searchResults->count());

        // Retrieve the facet again and assert values.
        $facet = $this->searchPage->facets[$this->taxonomyTerms[1]['#tid']];
        $this->assertFacetLinks(
            $facet,
            array(),
            array(
                $this->taxonomyTerms[1][1]['#tid'] => true,
                $this->taxonomyTerms[1][1][2]['#tid'] => true,
            )
        );

        // Reset the facet clicking on the first checkbox.
        $facet->getActiveLinkByValue($this->taxonomyTerms[1][1]['#tid'])->click();

        // Assert that both facets are present again.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Start testing the node with two terms.
        // This node is tagged like this:
        // - 1
        // -- 1-2 x
        // - 3
        // -- 3-1
        // --- 3-1-1 x
        // Start with selecting the term which has been directly tagged into
        // the node.
        $facets[$this->taxonomyTerms[1]['#tid']]
            ->getInactiveLinkByValue($this->taxonomyTerms[1][2]['#tid'])
            ->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Verify that we have 4 results.
        $this->assertEquals(4, $this->searchPage->searchResults->count());

        // Assert that our shared result is there.
        $this->assertTrue($this->findSearchResultByTitle($multiple_terms_node_title));

        // Assert that both facets are still present.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Verify statuses of the facet links in both facets.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[1]['#tid']],
            array(
                $this->taxonomyTerms[1][2][1]['#tid'] => 1,
                $this->taxonomyTerms[1][2][2]['#tid'] => 1,
            ),
            array(
                $this->taxonomyTerms[1][2]['#tid'] => true,
            )
        );

        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[3]['#tid']],
            array(
                $this->taxonomyTerms[3][1]['#tid'] => 1,
            ),
            array()
        );

        // Now select the 1-2-1 term, the other facet should disappear
        // and the results will go down to 1.
        $facets[$this->taxonomyTerms[1]['#tid']]
            ->getInactiveLinkByValue($this->taxonomyTerms[1][2][1]['#tid'])
            ->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Verify that we have one result only.
        $this->assertEquals(1, $this->searchPage->searchResults->count());

        // Assert that our shared result is not there anymore.
        $this->assertFalse($this->findSearchResultByTitle($multiple_terms_node_title));

        // Assert that we have only the first facet now.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array_keys($facets), array(
            $this->taxonomyTerms[1]['#tid'],
        ));

        // Verify the status of this facet.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[1]['#tid']],
            array(),
            array(
                $this->taxonomyTerms[1][2]['#tid'] => true,
                $this->taxonomyTerms[1][2][1]['#tid'] => true,
            )
        );

        // Deselect the last term.
        $facets[$this->taxonomyTerms[1]['#tid']]
            ->getActiveLinkByValue($this->taxonomyTerms[1][2][1]['#tid'])
            ->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Verify that we have 4 results again.
        $this->assertEquals(4, $this->searchPage->searchResults->count());

        // Assert that our shared result is back now.
        $this->assertTrue($this->findSearchResultByTitle($multiple_terms_node_title));

        // Assert that both facets are present again.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Select now the 3-1 term.
        $facets[$this->taxonomyTerms[3]['#tid']]
            ->getInactiveLinkByValue($this->taxonomyTerms[3][1]['#tid'])
            ->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $this->assertEquals(1, $this->searchPage->searchResults->count());

        // Assert that our shared result is still there.
        $this->assertTrue($this->findSearchResultByTitle($multiple_terms_node_title));

        // Assert that both facets are present again.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Assert the new facets status.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[1]['#tid']],
            array(),
            array(
                $this->taxonomyTerms[1][2]['#tid'] => true,
            )
        );

        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[3]['#tid']],
            array(
                $this->taxonomyTerms[3][1][1]['#tid'] => 1,
            ),
            array(
                $this->taxonomyTerms[3][1]['#tid'] => true,
            )
        );

        // Narrow down to 3-1-1 term.
        $facets[$this->taxonomyTerms[3]['#tid']]
            ->getInactiveLinkByValue($this->taxonomyTerms[3][1][1]['#tid'])
            ->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $this->assertEquals(1, $this->searchPage->searchResults->count());

        // Assert that our shared result is still there.
        $this->assertTrue($this->findSearchResultByTitle($multiple_terms_node_title));

        // Assert that both facets are still present.
        $facets = $this->searchPage->facets;
        $this->assertCount(2, $facets);
        $this->assertEquals(array_keys($facets), array_keys($configured_facet_tids));

        // Assert the new facets status.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[1]['#tid']],
            array(),
            array(
                $this->taxonomyTerms[1][2]['#tid'] => true,
            )
        );

        // Assert the facet status.
        $this->assertFacetLinks(
            $facets[$this->taxonomyTerms[3]['#tid']],
            array(),
            array(
                $this->taxonomyTerms[3][1]['#tid'] => true,
                $this->taxonomyTerms[3][1][1]['#tid'] => true,
            )
        );
    }

    /**
     * Tests that the facet links shown in the facets are sorted
     * in the correct order: term weight, active status, display name and
     * count.
     *
     * @group facets
     */
    public function testFacetLinksSorting()
    {
        // Keep the vocabulary id for later usage.
        $vid = taxonomy_vocabulary_machine_name_load('paddle_general')->vid;

        // Prepare prefix for the multi-level taxonomy.
        $prefix = $this->alphanumericTestDataProvider->getValidValue(4) . ' ';

        // Create a simple structure of 3 levels.
        // [1]
        // [1][1]
        // [1][2]
        $root_tid = $this->taxonomyService->createTerm($vid, "$prefix parent");

        // The children names have been picked on purpose. The first term
        // is alphabetically after the second term. When creating terms in our
        // distro (even through interface), they get a 0 weight value
        // until the taxonomy manager page gets saved once.
        // The taxonomy manager page orders terms by weight and then by name.
        // Our facets must behave the same way.
        $first_child_tid = $this->taxonomyService->createTerm($vid, "$prefix s", $root_tid);
        $second_child_tid = $this->taxonomyService->createTerm($vid, "$prefix f", $root_tid);

        // Mark the parent for tear down deletion.
        $this->taxonomyTerms = array(array('#tid' => $root_tid));

        // Prepare a prefix to be used for page titles. This will help us to
        // have search results.
        $title_prefix = $this->alphanumericTestDataProvider->getValidValue(8) . ' ';

        // Create one node per children.
        $this->nodes[] = $this->createNodeForTerms($first_child_tid, false, $title_prefix);
        $this->nodes[] = $this->createNodeForTerms($second_child_tid, false, $title_prefix);

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Select the term to be shown as facet.
        $this->configurePage->go();
        foreach ($this->configurePage->facetTermsCheckboxes->getAll() as $value => $checkbox) {
            ($value == $root_tid) ? $checkbox->check() : $checkbox->uncheck();
        }
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();

        // Now search the pages created, using the title prefix.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($title_prefix);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Assert that we find 2 results (one per node created).
        $this->assertEquals(2, $this->searchPage->searchResults->count());

        // Assert that our facet is there.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertNotEmpty($facets[$root_tid]);

        // Assert the facet links ordering.
        $expected_order = array(
            $second_child_tid,
            $first_child_tid,
        );
        $this->assertEquals($expected_order, array_keys($facets[$root_tid]->getInactiveLinks()));

        // Go to the general tag vocabulary page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Expand the children of the root term.
        $voculary_table = $this->taxonomyOverviewPage->vocabularyTable;
        $root_term_row = $voculary_table->getTermRowsByTid($root_tid);
        $root_term_row->linkShowChildTerms->click();
        $root_term_row->waitUntilChildTermsArePresent();

        // Move the second child tid under the first child.
        $voculary_table->changeTermPosition(
            $voculary_table->getTermRowsByTid($second_child_tid),
            1,
            $root_tid
        );
        $this->taxonomyOverviewPage->contextualToolbar->buttonSave->click();
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Do the search again.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert the new facet links ordering.
        $expected_order = array(
            $first_child_tid,
            $second_child_tid,
        );
        $this->assertEquals($expected_order, array_keys($this->searchPage->facets[$root_tid]->getInactiveLinks()));
    }

    /**
     * Test facets reaction on taxonomy vocabulary/terms changes.
     *
     * @group facets
     * @group search
     */
    public function testTaxonomyChanges()
    {
        // Keep the vocabulary id for later usage.
        $vid = taxonomy_vocabulary_machine_name_load('paddle_general')->vid;

        // Prepare prefix for the multi-level taxonomy.
        $prefix = $this->alphanumericTestDataProvider->getValidValue(4) . ' ';

        // Create a simple structure of 2 levels.
        // [1]
        // [1][1]
        $this->taxonomyTerms = $this->taxonomyService->createHierarchicalStructure($vid, 2, 1, 0, $prefix);

        // Make access to the root term easy.
        $root_tid = $this->taxonomyTerms[1]['#tid'];

        // Prepare a prefix to be used for page titles. This will help us to
        // have search results.
        $title_prefix = $this->alphanumericTestDataProvider->getValidValue(8) . ' ';

        // Create one node for each level.
        array_walk_recursive($this->taxonomyTerms, array($this, 'createNodeForTerms'), $title_prefix);

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Select the current root term to be shown as facet.
        $this->configurePage->go();
        foreach ($this->configurePage->facetTermsCheckboxes->getAll() as $value => $checkbox) {
            ($value == $root_tid) ? $checkbox->check() : $checkbox->uncheck();
        }
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->waitUntilPageIsLoaded();

        // Now search the pages created, using the title prefix.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert that our facet is there.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertNotEmpty($facets[$root_tid]);

        // Create a new root term.
        $new_root_tid = $this->taxonomyService->createTerm($vid, "$prefix new root");

        // Mark the parent for tear down deletion.
        $this->taxonomyTerms = array(array('#tid' => $new_root_tid));

        // Go to the general tag vocabulary page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Move the original root term as child of the new root term.
        $voculary_table = $this->taxonomyOverviewPage->vocabularyTable;
        $voculary_table->changeTermPosition(
            $voculary_table->getTermRowsByTid($root_tid),
            $voculary_table->getTermPositionByTid($new_root_tid) + 1,
            null,
            true
        );
        $this->taxonomyOverviewPage->contextualToolbar->buttonSave->click();
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Do the search again.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert that our facet disappeared.
        $facets = $this->searchPage->facets;
        $this->assertCount(0, $facets);
        $this->assertEmpty($facets[$root_tid]);

        // Go to the taxonomy overview page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Expand the children of the root term.
        $voculary_table = $this->taxonomyOverviewPage->vocabularyTable;
        $new_root_term_row = $voculary_table->getTermRowsByTid($new_root_tid);
        $new_root_term_row->linkShowChildTerms->click();
        $new_root_term_row->waitUntilChildTermsArePresent();

        // Put the original root back in place.
        $voculary_table->changeTermPosition(
            $voculary_table->getTermRowsByTid($root_tid),
            0,
            null,
            false
        );
        $this->taxonomyOverviewPage->contextualToolbar->buttonSave->click();
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Do a search again.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert that our facet is back in place.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertNotEmpty($facets[$root_tid]);

        // Go to the general tag vocabulary page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Edit the root term to have another title.
        $this->taxonomyOverviewPage->vocabularyTable->getTermRowsByTid($root_tid)->linkEdit->click();
        $modal = new OverviewPageCreateTermModal($this);
        $modal->waitUntilOpened();
        // This modal is old and weird. Initialize values.
        $modal->initializeFormElements(array('name'));

        // Put a new title.
        $new_term_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->formElementName->clear();
        $modal->formElementName->value($new_term_title);
        $modal->submit();
        $modal->waitUntilClosed();

        // Do a search again.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert that our facet is there.
        $facets = $this->searchPage->facets;
        $this->assertCount(1, $facets);
        $this->assertNotEmpty($facets[$root_tid]);

        // Assert that the facet title changed.
        $this->assertTextPresent($new_term_title);
        // Assert that 'Filter by' has been removed.
        $this->assertTextNotPresent('Filter by');

        // Go to the taxonomy overview page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Delete our root term.
        $this->taxonomyOverviewPage->deleteTerm($root_tid);

        // Do a search again.
        $this->searchPage->go();
        $this->searchPage->form->keywords->fill($title_prefix);
        $this->searchPage->form->submit->click();
        $this->searchPage->waitUntilPageIsLoaded();

        // Assert that our facet disappeared.
        $facets = $this->searchPage->facets;
        $this->assertCount(0, $facets);
        $this->assertEmpty($facets[$root_tid]);
    }

    /**
     * Create a new node for the specified term id.
     *
     * @param array|string $tids
     *   The taxonomy term ids to tag the node with.
     * @param int $key
     *   The key of the current array element.
     * @param string $title_prefix
     *   A prefix to be appended on the title.
     *
     * @return int
     *   The nid of the node created.
     */
    protected function createNodeForTerms($tids, $key, $title_prefix)
    {
        // Convert single value tids into array.
        if (!is_array($tids)) {
            $tids = array($tids);
        }

        $title = $title_prefix . $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        $node = node_load($nid);

        /* @ var \EntityMetadataWrapper $wrapper */
        $wrapper = entity_metadata_wrapper('node', $node);
        $wrapper->field_paddle_general_tags->set($tids);
        $wrapper->save();

        // Publish the node.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Keep a reference of created node nids.
        $this->nodes[$nid] = $nid;

        return $nid;
    }

    /**
     * Verify a facet against the structure set up.
     *
     * @param \Kanooh\Paddle\Pages\Element\Search\Facet $facet
     *   The facet to verify.
     * @param array $expected_inactive
     *   The expected inactive links.
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
        }
    }

    /**
     * Search amongst search results to find the specified title.
     *
     * @param string $title
     *   The title to find.
     *
     * @return bool
     *   The result of the search.
     */
    protected function findSearchResultByTitle($title)
    {
        // Loop all the items and try to find the title.
        foreach ($this->searchPage->searchResults->getResults() as $result) {
            if ($result->title === $title) {
                // Title found, stop looping.
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all the taxonomy terms created by this test.
        if (!empty($this->taxonomyTerms)) {
            foreach ($this->taxonomyTerms as $key => $structure) {
                // Deleting parents will delete children in a non-multi parent
                // environment.
                taxonomy_term_delete($structure['#tid']);
            }
        }

        // Delete all nodes created by this test.
        if (!empty($this->nodes)) {
            node_delete_multiple($this->nodes);
        }

        parent::tearDown();
    }
}
