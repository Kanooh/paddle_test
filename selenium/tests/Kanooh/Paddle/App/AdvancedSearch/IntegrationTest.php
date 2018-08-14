<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\IntegrationTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Tests that content filter, facets and search form interact together correctly.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class IntegrationTest extends AdvancedSearchTestBase
{
    /**
     * Tests that everything works together properly.
     *
     * @group facets
     * @group search
     */
    public function testEverything()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();

        // Create 3 root terms.
        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $terms = $this->taxonomyService->createHierarchicalStructure($vid, 3, 3, 0, $prefix);

        // Create a shared word to use between the basic and landing pages.
        $shared = $this->alphanumericTestDataProvider->getValidValue();

        // Create a basic page tagged with a few terms.
        $basic_title = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $shared;
        $this->createNodeForTerms(array(
            $terms[1][1][1]['#tid'],
            $terms[2][1][1]['#tid'],
        ), $basic_title);

        // Now a landing page. We need a custom callback because createLandingPage()
        // requires the title as second parameter.
        $test_case = $this;
        $landing_callback = new SerializableClosure(
            function ($title) use ($test_case) {
                return $test_case->contentCreationService->createLandingPage(null, $title);
            }
        );
        $landing_title = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $shared;
        $this->createNodeForTerms(array(
            $terms[1][1][2]['#tid'],
            $terms[2][2][2]['#tid'],
        ), $landing_title, $landing_callback);

        // Also an overview page.
        $overview_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->createNodeForTerms(array(
            $terms[1][1][3]['#tid'],
            $terms[2][3][3]['#tid'],
            $terms[3][1]['#tid'],
        ), $overview_title, array($this->contentCreationService, 'createOverviewPage'));
        
        // Create an untagged basic page too.
        $untagged_title = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $shared;
        $untagged_page = $this->contentCreationService->createBasicPage($untagged_title);
        $this->publishPage($untagged_page);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 4 results.
        $this->assertCount(4, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        $this->assertArrayHasKey($overview_title, $results);
        $this->assertArrayHasKey($untagged_title, $results);

        // Enable the facets.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->enabled->check();
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->enabled->check();
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[3]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged results.
        $this->assertCount(3, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        $this->assertArrayHasKey($overview_title, $results);
        // Verify that all the facets are shown.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(3, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 3,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
                $terms[2][3]['#tid'] => 1,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[3]['#tid']],
            array(
                $terms[3][1]['#tid'] => 1,
            ),
            array()
        );

        // Edit the node and enable the basic page content filter.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged basic page.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($basic_title, $results);
        // Verify that only the first and second root term facets are shown,
        // as the third facet contains only an overview page.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 1,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
            ),
            array()
        );

        // Edit the node and enable the landing page content filter.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('landing_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged basic and landing pages.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that only the first and second root term facets are shown, for
        // the same reason as before.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 2,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
            ),
            array()
        );

        // Edit the node and enable the overview page content filter.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('paddle_overview_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged pages.
        $this->assertCount(3, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        $this->assertArrayHasKey($overview_title, $results);
        // Verify that all the facets are shown now.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(3, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 3,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
                $terms[2][3]['#tid'] => 1,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[3]['#tid']],
            array(
                $terms[3][1]['#tid'] => 1,
            ),
            array()
        );

        // Select the second child of the second root term. Only the landing
        // page is tagged by this term.
        $facets[$terms[2]['#tid']]->getInactiveLinkByValue($terms[2][2]['#tid'])->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the landing page.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that we have only two facets. The third root term facet has
        // disappeared because only the overview page is tagged with its terms.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 1,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][2][2]['#tid'] => 1,
            ),
            array(
                $terms[2][2]['#tid'] => 1,
            )
        );

        // Edit the node and set the first root term facet as hidden.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that results didn't change.
        $this->assertCount(3, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        $this->assertArrayHasKey($overview_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
                $terms[2][3]['#tid'] => 1,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[3]['#tid']],
            array(
                $terms[3][1]['#tid'] => 1,
            ),
            array()
        );

        // Launch a search using the untagged title.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($untagged_title);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Verify that no results are shown.
        $this->assertEmpty($this->frontendViewPage->searchResultsPane->results->getResults());
        $this->assertTextPresent('Your search yielded no results.');

        // Edit the node and set the first root term facet as visible, but disable
        // overview pages.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->mode->list->select();
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('paddle_overview_page')->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that no overview pages are shown anymore.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 2,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
            ),
            array()
        );

        // Launch a search using the overview title.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($overview_title);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Verify that no results are shown.
        $this->assertEmpty($this->frontendViewPage->searchResultsPane->results->getResults());
        $this->assertTextPresent('Your search yielded no results.');

        // Launch a search using the shared title word.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($shared);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify the tagged basic and landing pages are shown. With this
        // assertion we verify that doing a search respects enabled facets,
        // since the untagged page is not shown, and content filtering, as the
        // overview page is not shown either.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 2,
            ),
            array()
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
            ),
            array()
        );

        // Expand the first root term facet.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][1]['#tid'])->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that pages are still shown.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1][1]['#tid'] => 1,
                $terms[1][1][2]['#tid'] => 1,
            ),
            array(
                $terms[1][1]['#tid'] => 2,
            )
        );
        $this->assertFacetLinks(
            $facets[$terms[2]['#tid']],
            array(
                $terms[2][1]['#tid'] => 1,
                $terms[2][2]['#tid'] => 1,
            ),
            array()
        );

        // Select the second root term child the basic page is selected with.
        $facets[$terms[2]['#tid']]->getInactiveLinkByValue($terms[2][1]['#tid'])->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that only the basic page is shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($basic_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1][1]['#tid'] => 1,
            ),
            array(
                $terms[1][1]['#tid'] => 1,
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

        // Edit the page and set the second facet as dropdown view mode.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable
            ->rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that overview pages are still not shown.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the structure for the first facet.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 2,
            ),
            array()
        );
        // And the second one, that now is rendered as dropdown.
        $this->assertEquals(
            array(
                $prefix . '2-1',
                '- ' . $prefix . '2-1-1',
                $prefix . '2-2',
                '- ' . $prefix . '2-2-2',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the empty option.
        $this->assertEquals('- Choose -', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Select a term in the second facet.
        $facets[$terms[2]['#tid']]->select->selectOptionByLabel('- ' . $prefix . '2-1-1');
        $this->frontendViewPage->waitUntilPageIsLoaded();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that only the basic page is shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($basic_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the first facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1]['#tid'] => 1,
            ),
            array()
        );
        // And the second one.
        $this->assertEquals(
            array(
                '- Choose -',
                $prefix . '2-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is the selected term.
        $this->assertEquals('- ' . $prefix . '2-1-1', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Expand the first root term facet.
        $facets[$terms[1]['#tid']]->getInactiveLinkByValue($terms[1][1]['#tid'])->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that only the basic page is shown.
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($basic_title, $results);
        // Verify that we have the correct number of facets.
        $facets = $this->frontendViewPage->facets;
        $this->assertCount(2, $facets);
        // Verify the facets structure.
        $this->assertFacetLinks(
            $facets[$terms[1]['#tid']],
            array(
                $terms[1][1][1]['#tid'] => 1,
            ),
            array(
                $terms[1][1]['#tid'] => 1,
            )
        );
        // And the second one.
        $this->assertEquals(
            array(
                '- Choose -',
                $prefix . '2-1',
            ),
            array_values($facets[$terms[2]['#tid']]->select->getOptions())
        );
        // Verify that the active value is kept.
        $this->assertEquals('- ' . $prefix . '2-1-1', $facets[$terms[2]['#tid']]->select->getSelectedLabel());

        // Edit the page and hide all the facets and enable all content types.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[1]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[2]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows[$terms[3]['#tid']]->mode->hidden->select();
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('paddle_overview_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged pages. This assertion verifies
        // that taxonomy filtering is working even when hidden.
        $this->assertCount(3, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
        $this->assertArrayHasKey($overview_title, $results);
        // Verify that we have no facets
        $facets = $this->frontendViewPage->facets;
        $this->assertEmpty($facets);

        // Launch a search using the shared title word.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($shared);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have only the tagged basic and landing pages.
        $this->assertCount(2, $results);
        $this->assertArrayHasKey($basic_title, $results);
        $this->assertArrayHasKey($landing_title, $results);
    }
}
