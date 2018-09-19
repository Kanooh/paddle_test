<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\AdvancedSearchSortingTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

/**
 * Performs tests on the Advanced Search Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AdvancedSearchSortingTest extends AdvancedSearchTestBase
{

    /**
     * Tests the configurable default sorting.
     */
    public function testDefaultSorting()
    {
        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the view page of the node.
        $this->frontendViewPage->go($nid);

        // Asserts that the default sorting is "Relevance - Descending".
        $relevance_sort = $this->frontendViewPage->sorting->relevance;

        $this->assertTrue($this->sortIsActive($relevance_sort));
        $this->assertFalse($this->sortIsAscending($relevance_sort));

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);

        // Edit the sorting values.
        $this->nodeEditPage->advancedSearchForm->defaultSortOption->publication_date->select();
        $this->nodeEditPage->advancedSearchForm->defaultSortOrder->asc->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the view page of the node.
        $this->frontendViewPage->go($nid);

        // Check whether the sort changed.
        $relevance_sort = $this->frontendViewPage->sorting->relevance;

        $this->assertFalse($this->sortIsActive($relevance_sort));

        $publication_date_sort = $this->frontendViewPage->sorting->publicationDate;
        $this->assertTrue($this->sortIsActive($publication_date_sort));
        $this->assertTrue($this->sortIsAscending($publication_date_sort));
    }

    /**
     * Checks whether the sort is active.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $sort
     *   The Sort element.
     *
     * @return bool
     *   Whether the sort is active.
     */
    protected function sortIsActive($sort)
    {
        $classes_string = trim($sort->attribute('class'));
        $classes = preg_split('/\s+/', $classes_string);
        return in_array('active-sort', $classes);
    }

    /**
     * Checks whether the sort is ascending.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $sort
     *   The Sort element.
     *
     * @return bool
     *   Whether the sort is ascending.
     */
    protected function sortIsAscending($sort)
    {
        $classes_string = trim($sort->attribute('class'));
        $classes = preg_split('/\s+/', $classes_string);
        return in_array('sort-asc', $classes);
    }


    /**
     * Tests the sorting on relevance.
     */
    public function testRelevanceSorting()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Define a common word.
        $word = $this->alphanumericTestDataProvider->getValidValue();

        // Create a node with the word as title.
        $nid_1 = $this->contentCreationService->createBasicPage($word);
        $this->publishPage($nid_1);

        // Create a node with the word in the body field.
        $title_2 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_2 = $this->contentCreationService->createBasicPage($title_2);
        $text = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $word;
        $this->nodeEditPage->go($nid_2);
        $this->nodeEditPage->body->setBodyText($text);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid_2);

        // Create a node with the word in the title.
        $title_3 = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $word;
        $nid_3 = $this->contentCreationService->createBasicPage($title_3);
        $this->publishPage($nid_3);

        $asp_nid = $this->contentCreationService->createAdvancedSearchPage();

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        $this->frontendViewPage->go($asp_nid);
        // By default the search results are sorted by relevance.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($word);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();
        $results = $this->frontendViewPage->searchResultsPane->results->getResults();

        $this->assertCount(3, $results);
        $this->assertEquals($word, $results[0]->title);
        $this->assertEquals($title_3, $results[1]->title);
        $this->assertEquals($title_2, $results[2]->title);
    }

    /**
     * Tests the sorting on title.
     */
    public function testTitleSorting()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Prepend the "M" to be in the middle of the alphabet.
        $word = 'm ' . $this->alphanumericTestDataProvider->getValidValue();
        $nid_1 = $this->contentCreationService->createBasicPage($word);
        $this->publishPage($nid_1);

        // Prepend the "A" to be in the beginning of the alphabet.
        $title_2 = 'a' . $this->alphanumericTestDataProvider->getValidValue() . ' ' . $word;
        $nid_2 = $this->contentCreationService->createBasicPage($title_2);
        $this->publishPage($nid_2);

        $title_3 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_3 = $this->contentCreationService->createBasicPage($title_3);
        $this->publishPage($nid_3);

        // Prepend the "Z" to be in the end of the alphabet. This was all done to check the actual sorting.
        $title_4 = 'z' . $this->alphanumericTestDataProvider->getValidValue() . ' ' . $word;
        $nid_4 = $this->contentCreationService->createBasicPage($title_4);
        $this->publishPage($nid_4);

        $asp_nid = $this->contentCreationService->createAdvancedSearchPage();

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        $this->frontendViewPage->go($asp_nid);
        $this->frontendViewPage->searchFormPane->form->keywords->fill($word);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();
        $this->frontendViewPage->sorting->title->click();
        $this->frontendViewPage->checkArrival();
        $results = $this->frontendViewPage->searchResultsPane->results->getResults();

        $this->assertCount(3, $results);
        $this->assertEquals($word, $results[1]->title);
        $this->assertEquals($title_2, $results[0]->title);
        $this->assertEquals($title_4, $results[2]->title);
    }

    /**
     * Tests the sorting on publication date.
     */
    public function testPublicationDateSorting()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Create three nodes and publish them.
        $title_1 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_1 = $this->contentCreationService->createBasicPage($title_1);
        $this->publishPage($nid_1);

        $title_2 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_2 = $this->contentCreationService->createBasicPage($title_2);
        $this->publishPage($nid_2);

        $title_3 = $this->alphanumericTestDataProvider->getValidValue();
        $nid_3 = $this->contentCreationService->createBasicPage($title_3);
        $this->publishPage($nid_3);

        $asp_nid = $this->contentCreationService->createAdvancedSearchPage();

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the frontend view and sort by publication date descending.
        $this->frontendViewPage->go($asp_nid);
        $this->frontendViewPage->sorting->publicationDate->click();
        $this->frontendViewPage->checkArrival();
        $results = $this->frontendViewPage->searchResultsPane->results->getResults();

        // Verify that the order of the nodes reflect the publication time.
        $this->assertEquals($title_3, $results[0]->title);
        $this->assertEquals($title_2, $results[1]->title);
        $this->assertEquals($title_1, $results[2]->title);
    }
}
