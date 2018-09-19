<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\FacetsTest.
 */

namespace Kanooh\Paddle\App\Maps;

use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPageCreateTermModal;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Tests the facets behaviour for the Maps paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FacetsTest extends MapsTestBase
{
    /**
     * Tests filtering based on enabled vocabulary terms.
     *
     * @group facets
     */
    public function testVocabularyTermsFilterWhenEnabled()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();
        // Create three root terms with three children each = 12.
        $terms = $this->taxonomyService
            ->createHierarchicalStructure(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, 2, 3, 0, $prefix);

        // Create one basic page for each level.
        $titles = $this->createNodeForTermStructure($terms);
        
        // Create 2 extra nodes not tagged with anything.
        $organization_page_title = $this->alphanumericTestDataProvider->getValidValue();
        $organization_nid = $this->createNodeOrganizationalUnit($organization_page_title);
        $this->publishPage($organization_nid);

        // @TODO: set this back to contact person when functionality is ready.
        // Get a valid address.
        $ou_address = $this->addressTestDataProvider->getValidValue();

        // $first_name = $this->alphanumericTestDataProvider->getValidValue();
        //$last_name = $this->alphanumericTestDataProvider->getValidValue();
        $ou_page_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->publishPage(
            $this->contentCreationService->createOrganizationalUnit(
                $ou_page_title,
                array(
                    'field_paddle_ou_address' => array(
                        'thoroughfare' => $ou_address['thoroughfare'],
                        'postal_code' =>  $ou_address['postal_code'],
                        'locality' => $ou_address['locality'],
                        'country' => $ou_address['country'],
                    )
                )
            )
        );

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that all the nodes are shown.
        $this->assertCount(14, $results);
        $this->assertResultsByTermStructure($terms, $titles, $results);
        $this->assertArrayHasKey($organization_page_title, $results);
        $this->assertArrayHasKey($ou_page_title, $results);

        // Edit the maps page.
        $this->nodeEditPage->go($nid);
        // Enable the first term.
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();

        // Save the page and preview it.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 4 nodes shown only.
        $this->assertCount(4, $results);
        $this->assertResultsByTermStructure($terms[1], $titles, $results);

        // Enable the second term now.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        // Set it to appear as dropdown, so we can verify that filtering is
        // applied for each display mode used.
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend again and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 8 nodes shown.
        $this->assertCount(8, $results);
        $this->assertResultsByTermStructure($terms[1], $titles, $results);
        $this->assertResultsByTermStructure($terms[2], $titles, $results);

        // Enable the third term with hidden display mode.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[3]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[3]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend again and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have all the nodes shown.
        $this->assertCount(12, $results);
        $this->assertResultsByTermStructure($terms[1], $titles, $results);
        $this->assertResultsByTermStructure($terms[2], $titles, $results);
        $this->assertResultsByTermStructure($terms[3], $titles, $results);

        // Disable the second term.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend again and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we are back to 8 nodes.
        $this->assertCount(8, $results);
        $this->assertResultsByTermStructure($terms[1], $titles, $results);
        $this->assertResultsByTermStructure($terms[3], $titles, $results);
    }

    /**
     * Tests correct filtering on nodes tagged with multiple terms.
     *
     * @group facets
     */
    public function testMultipleTermsTaggedNodeFilter()
    {

        $this->markTestSkipped('Will onyl use this test when the contact person can be used by the maps content type.');
        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();
        // Create two root terms with two children each = 6.
        $terms = $this->taxonomyService
            ->createHierarchicalStructure(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, 2, 2, 0, $prefix);

        // Create a node tagged with two terms.
        $first_name = $this->alphanumericTestDataProvider->getValidValue();
        $last_name = $this->alphanumericTestDataProvider->getValidValue();
        $title = $first_name . ' ' . $last_name;

        $this->createContactPersonNodeForTerms(array(
            $terms[1][2]['#tid'],
            $terms[2][1]['#tid'],
        ), $first_name, $last_name);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the first term.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that our node is shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($title, $results);

        // Now enable the second term and disable the first.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->uncheck();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        // Set the display mode to dropdown, so we can verify that every filter works.
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that our node is still shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($title, $results);

        // Now enable again the first term and set it as hidden.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        // Set the display mode to hidden, so we can verify that every filter works.
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[1]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that our node is still shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($title, $results);
    }

    /**
     * Tests the list display mode for the facets.
     *
     * @group facets
     */
    public function testListFacets()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();
        
        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();

        // Create 2 root terms.
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 1, 2, 0, $prefix);

        // Make the first term have 2 levels with 2 terms each underneath.
        $terms[1] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 2, $terms[1]['#tid'], "{$prefix}1-");

        // Second term has 2 flat children.
        $terms[2] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 1, 2, $terms[2]['#tid'], "{$prefix}2-");

        // Create one basic page for each level.
        $titles = $this->createNodeForTermStructure($terms);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that all the nodes are shown:
        // - 1 + (1 * 2) + (2 * 2) on the first level
        // - 1 + 2 on the second level
        $this->assertCount(10, $results);
        $this->assertResultsByTermStructure($terms, $titles, $results);

        // Verify the facets output.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Facet for the first root term.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 3,
                $terms[1][2]['#tid'] => 3,
            ),
            array()
        );
        // Facet for the second root term.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
            ),
            array()
        );

        // Select the first term in the first facet.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Assert that we have the correct results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(3, $results);
        $this->assertResultsByTermStructure($terms[1][1], $titles, $results);

        // Assert that we have only one facet now.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array($terms[1]['#tid']), array_keys($facets));

        // Verify the facet output showing the child terms.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1][1]['#tid'] => 1,
                $terms[1][1][2]['#tid'] => 1,
            ),
            array(
                $terms[1][1]['#tid'] => 3,
            )
        );

        // Click a child term link.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][1][2]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Assert the correct number of results.
        // Assert that we have the correct results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertResultsByTermStructure($terms[1][1][2], $titles, $results);

        // Assert that we have the same facet showing and its values.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array($terms[1]['#tid']), array_keys($facets));
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(),
            array(
                $terms[1][1]['#tid'] => 1,
                $terms[1][1][2]['#tid'] => 1,
            )
        );

        // Reset the facet clicking on the first checkbox.
        $facets[$terms[1]['#tid']]->getActiveLinkByValue($terms[1][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have all the facets again.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Click the first term of the second facet.
        $facets[$terms[2]['#tid']]->getInactiveLinkByValue($terms[2][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only one result.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertResultsByTermStructure($terms[2][1], $titles, $results);
        // And only one facet.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertArrayHasKey($terms[2]['#tid'], $facets);

        // Verify the facet output.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(),
            array(
                $terms[2][1]['#tid'] => 1,
            )
        );
    }

    /**
     * Tests list facets behaviour when nodes are tagged with multiple terms.
     *
     * @group facets
     */
    public function testMultipleTermsNodesListFacetBehaviour()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();

        // Create 2 root terms.
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 1, 2, 0, $prefix);

        // Make the first term have 2 levels with 2 terms each underneath.
        $terms[1] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 2, $terms[1]['#tid'], "{$prefix}1-");

        // Second term has 2 levels with 1 term each.
        $terms[2] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 1, $terms[2]['#tid'], "{$prefix}2-");

        // Add a node in the furthest first term leaf, to allow full facet
        // rendering.
        $leaf_node_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->createNodeForTerms($terms[1][2][1]['#tid'], $leaf_node_title);

        // Add a node that is tagged with two terms.
        $multiple_terms_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->createNodeForTerms(array(
            $terms[1][2]['#tid'],
            $terms[2][1][1]['#tid'],
        ), $multiple_terms_title);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that all the nodes are shown.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Verify the facets output.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Facet for the first root term.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][2]['#tid'] => 2,
            ),
            array()
        );
        // Facet for the second root term.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
            ),
            array()
        );

        // Start with selecting the term which has been directly tagged into
        // the node.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][2]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have 2 results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are still present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Verify statuses of the facet links in both facets.
        // First facet.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][2][1]['#tid'] => 1,
            ),
            array(
                $terms[1][2]['#tid'] => 2,
            )
        );
        // Second facet.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
            ),
            array()
        );

        // Now select the 1-2-1 term, the other facet should disappear
        // and the results will go down to 1.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][2][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have one result only.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);

        // Assert that we have only the first facet now.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertArrayHasKey($terms[1]['#tid'], $facets);

        // Verify the status of this facet.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(),
            array(
                $terms[1][2]['#tid'] => 1,
                $terms[1][2][1]['#tid'] => 1,
            )
        );

        // Deselect the last term.
        $facets[$terms[1]['#tid']]->getActiveLinkByValue($terms[1][2][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have 2 results again.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are again present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Select now the 2-1 term.
        $facets = $this->frontendViewPage->facets;
        $facets[$terms[2]['#tid']]->getInactiveLinkByValue($terms[2][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Assert the new facets status.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(),
            array(
                $terms[1][2]['#tid'] => 1,
            )
        );

        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1][1]['#tid'] => 1,
            ),
            array(
                $terms[2][1]['#tid'] => 1,
            )
        );

        // Narrow down to 2-1-1 term.
        $facets[$terms[2]['#tid']]->getInactiveLinkByValue($terms[2][1][1]['#tid'])->click();
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are still present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Assert the new facets status.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(),
            array(
                $terms[1][2]['#tid'] => 1,
            )
        );

        // Assert the facet status.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(),
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][1][1]['#tid'] => 1,
            )
        );
    }

    /**
     * Test facets reaction on taxonomy vocabulary/terms changes.
     *
     * @group facets
     *
     * @todo refactor as a base test
     */
    public function testTaxonomyChanges()
    {
        // Prepare prefix for the multi-level taxonomy.
        $prefix = $this->alphanumericTestDataProvider->getValidValue(4) . ' ';

        // Create a simple structure of 2 levels.
        // [1]
        // [1][1]
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 2, 1, 0, $prefix);

        // Make access to the root term easy.
        $root_tid = $terms[1]['#tid'];

        // Create one basic page for each level.
        $this->createNodeForTermStructure($terms);

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend node view.
        $this->frontendViewPage->go($nid);
        // Assert that our facet is there.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertNotEmpty($facets[$root_tid]);

        // Create a new root term.
        $new_root_tid = $this->taxonomyService->createTerm($vid, "$prefix new root");

        // Go to the general tag vocabulary page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Move the original root term as child of the new root term.
        $vocabulary_table = $this->taxonomyOverviewPage->vocabularyTable;
        $vocabulary_table->changeTermPosition(
            $vocabulary_table->getTermRowsByTid($root_tid),
            $vocabulary_table->getTermPositionByTid($new_root_tid) + 1,
            null,
            true
        );
        $this->taxonomyOverviewPage->contextualToolbar->buttonSave->click();
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Go to the frontend node view again.
        $this->frontendViewPage->go($nid);
        // Assert that our facet disappeared.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(0, $facets);
        $this->assertEmpty($facets[$root_tid]);

        // Go to the taxonomy overview page.
        $this->taxonomyOverviewPage->go(array(2));
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Expand the children of the root term.
        $vocabulary_table = $this->taxonomyOverviewPage->vocabularyTable;
        $new_root_term_row = $vocabulary_table->getTermRowsByTid($new_root_tid);
        $new_root_term_row->linkShowChildTerms->click();
        $new_root_term_row->waitUntilChildTermsArePresent();

        // Put the original root back in place.
        $vocabulary_table->changeTermPosition(
            $vocabulary_table->getTermRowsByTid($root_tid),
            0,
            null,
            false
        );
        $this->taxonomyOverviewPage->contextualToolbar->buttonSave->click();
        $this->taxonomyOverviewPage->waitUntilPageIsLoaded();

        // Go to the frontend node view.
        $this->frontendViewPage->go($nid);
        // Assert that our facet is back in place.
        $facets = $this->frontendViewPage->facets;
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

        // Go to the frontend node view.
        $this->frontendViewPage->go($nid);
        // Assert that our facet is there.
        $facets = $this->frontendViewPage->facets;
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

        // Go to the frontend node view.
        $this->frontendViewPage->go($nid);
        // Assert that our facet disappeared.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(0, $facets);
        $this->assertEmpty($facets[$root_tid]);
    }

    /**
     * Tests the dropdown display mode for the facets.
     *
     * @group facets
     */
    public function testDropdownFacets()
    {
        $this->deleteExistingNodes();

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();

        // Create 2 root terms.
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 1, 2, 0, $prefix);

        // Make the first term have 2 levels with 2 terms each underneath.
        $terms[1] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 2, $terms[1]['#tid'], "{$prefix}1-");

        // Second term has 2 flat children.
        $terms[2] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 1, 2, $terms[2]['#tid'], "{$prefix}2-");

        // Create one basic page for each level.
        $titles = $this->createNodeForTermStructure($terms);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[1]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that all the nodes are shown:
        // - 1 + (1 * 2) + (2 * 2) on the first level
        // - 1 + 2 on the second level
        $this->assertCount(10, $results);
        $this->assertResultsByTermStructure($terms, $titles, $results);

        // Verify the facets output.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify that the first select contains the expected options.
        $this->assertEquals(
            array(
                $prefix . '1-1',
                '- ' . $prefix . '1-1-1',
                '- ' . $prefix . '1-1-2',
                $prefix . '1-2',
                '- ' . $prefix . '1-2-1',
                '- ' . $prefix . '1-2-2',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[1]['#tid']]->select->getSelectedLabel());
        // Do the same with the second facet.
        $this->assertEquals(
            array(
                $prefix . '2-1',
                $prefix . '2-2',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Select the first term in the first select.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel($prefix . '1-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Assert that we have the correct results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(3, $results);
        $this->assertResultsByTermStructure($terms[1][1], $titles, $results);

        // Assert that we have only one facet now.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array($terms[1]['#tid']), array_keys($facets));

        // Verify the facet output showing the child terms.
        // Verify that the first select contains the expected options.
        // The "- Choose -" option will be visible now because it allows us
        // to reset the facet. This is a limitation of Select::getOptions().
        $this->assertEquals(
            array(
                '- Choose -',
                '- ' . $prefix . '1-1-1',
                '- ' . $prefix . '1-1-2',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals($prefix . '1-1', $facets[$terms[1]['#tid']]->select->getSelectedLabel());

        // Select a child term.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel('- ' . $prefix . '1-1-2');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Assert the correct number of results.
        // Assert that we have the correct results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertResultsByTermStructure($terms[1][1][2], $titles, $results);

        // Assert that we have the same facet showing and its values.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertEquals(array($terms[1]['#tid']), array_keys($facets));
        $this->assertEquals(
            array(
                '- Choose -',
                $prefix . '1-1',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the last selected term option.
        $this->assertEquals('- ' . $prefix . '1-1-2', $facets[$terms[1]['#tid']]->select->getSelectedLabel());

        // Reset the facet selecting the empty option.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel('- Choose -');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have all the facets again.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Select the first term of the second facet dropdown.
        $facets[$terms[2]['#tid']]->select->selectOptionByLabel($prefix . '2-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only one result.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertResultsByTermStructure($terms[2][1], $titles, $results);
        // And only one facet.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertArrayHasKey($terms[2]['#tid'], $facets);

        // Verify the facet options.
        $this->assertEquals(
            array(
                '- Choose -',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals($prefix . '2-1', $facets[$terms[2]['#tid']]->select->getSelectedLabel());
    }

    /**
     * Tests dropdown facets behaviour when nodes are tagged with multiple terms.
     *
     * @group facets
     */
    public function testMultipleTermsNodesDropdownFacetBehaviour()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();

        // Create 2 root terms.
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 1, 2, 0, $prefix);

        // Make the first term have 2 levels with 2 terms each underneath.
        $terms[1] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 2, $terms[1]['#tid'], "{$prefix}1-");

        // Second term has 2 levels with 1 term each.
        $terms[2] += $this->taxonomyService
            ->createHierarchicalStructure($vid, 2, 1, $terms[2]['#tid'], "{$prefix}2-");

        // Add a node in the furthest first term leaf, to allow full facet
        // rendering.
        $leaf_node_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->createNodeForTerms($terms[1][2][1]['#tid'], $leaf_node_title);

        // Add a node that is tagged with two terms.
        $multiple_terms_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->createNodeForTerms(array(
            $terms[1][2]['#tid'],
            $terms[2][1][1]['#tid'],
        ), $multiple_terms_title);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an maps node.
        $nid = $this->contentCreationService->createMapsPage();

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[1]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        $this->nodeEditPage->mapsSearchForm->vocabularyTermsTable
            ->rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and get the results.
        $this->frontendViewPage->go($nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that all the nodes are shown.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Verify the facets output.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify that the first select contains the expected options.
        $this->assertEquals(
            array(
                $prefix . '1-2',
                '- ' . $prefix . '1-2-1',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[1]['#tid']]->select->getSelectedLabel());
        // Do the same with the second facet.
        $this->assertEquals(
            array(
                $prefix . '2-1',
                '- ' . $prefix . '2-1-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Start with selecting the term which has been directly tagged into
        // the node.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel($prefix . '1-2');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have 2 results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are still present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Verify statuses of the dropdowns in both facets.
        // First facet.
        $this->assertEquals(
            array(
                '- Choose -',
                '- ' . $prefix . '1-2-1',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals($prefix . '1-2', $facets[$terms[1]['#tid']]->select->getSelectedLabel());
        // Second facet.
        $this->assertEquals(
            array(
                $prefix . '2-1',
                '- ' . $prefix . '2-1-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Now select the 1-2-1 term, the other facet should disappear
        // and the results will go down to 1.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel('- ' . $prefix . '1-2-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have one result only.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);

        // Assert that we have only the first facet now.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(1, $facets);
        $this->assertArrayHasKey($terms[1]['#tid'], $facets);

        // Verify the status of this facet.
        $this->assertEquals(
            array(
                '- Choose -',
                $prefix . '1-2',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals('- ' . $prefix . '1-2-1', $facets[$terms[1]['#tid']]->select->getSelectedLabel());

        // Select again the parent of this term.
        $facets[$terms[1]['#tid']]->select->selectOptionByLabel($prefix . '1-2');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have 2 results again.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($leaf_node_title, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are again present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Select now the 2-1 term.
        $facets = $this->frontendViewPage->facets;
        $facets[$terms[2]['#tid']]->select->selectOptionByLabel($prefix . '2-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Assert the new facets status for the first facet.
        $this->assertEquals(
            array(
                '- Choose -',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value staid the same.
        $this->assertEquals($prefix . '1-2', $facets[$terms[1]['#tid']]->select->getSelectedLabel());
        // Verify the second facet.
        $this->assertEquals(
            array(
                '- Choose -',
                '- ' . $prefix . '2-1-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals($prefix . '2-1', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Narrow down to 2-1-1 term.
        $facets[$terms[2]['#tid']]->select->selectOptionByLabel('- ' . $prefix . '2-1-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Verify that we have only the shared result.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($multiple_terms_title, $results);

        // Assert that both facets are still present.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);

        // Assert the new facets status for the first facet.
        $this->assertEquals(
            array(
                '- Choose -',
            ),
            array_values($facets[$terms[1]['#tid']]->select->getOptions())
        );
        // Verify that the active value staid the same.
        $this->assertEquals($prefix . '1-2', $facets[$terms[1]['#tid']]->select->getSelectedLabel());
        // Verify the second facet.
        $this->assertEquals(
            array(
                '- Choose -',
                $prefix . '2-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals('- ' . $prefix . '2-1-1', $facets[$terms[2]['#tid']]->select->getSelectedLabel());
    }
}
