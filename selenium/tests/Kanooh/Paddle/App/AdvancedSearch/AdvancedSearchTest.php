<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\AdvancedSearch.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\VocabularyTermsTableRow;
use Kanooh\Paddle\Pages\SearchPage\SearchResult;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Performs tests on the Advanced Search Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AdvancedSearchTest extends AdvancedSearchTestBase
{

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Tests the default layout set when creating a new page.
     */
    public function testDefaultLayout()
    {
        // Create an advanced search page.
        $this->contentCreationService->createAdvancedSearchPageViaUI();
        $this->adminNodeViewPage->checkClassPresent('paddle-layout-paddle_2_col_3_9');
    }

    /**
     * Test if the "Change layout" functionality works properly.
     *
     * The change is applied and it is visible immediately.
     *
     * @group panes
     */
    public function testChangeLayout()
    {
        // Create an advanced search page and go to the page layout.
        $nid = $this->contentCreationService->createAdvancedSearchPage();
        $this->layoutPage->go($nid);

        // Get the current layout and supported layouts.
        $curr_layout = $this->layoutPage->display->getCurrentLayoutId();
        $allowed_layouts = $this->layoutPage->display->getSupportedLayouts();

        // Unset the current layout.
        unset($allowed_layouts[$curr_layout]);
        $random_layout = array_rand($allowed_layouts);

        // Change the layout.
        $this->layoutPage->changeLayout($random_layout);

        // Check that the correct layout is displayed.
        $ipe_placeholders_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "panels-ipe-placeholder")]';
        $this->waitUntilElementIsDisplayed($ipe_placeholders_xpath);
        $ipe_placeholders = $this->elements($this->using('xpath')->value($ipe_placeholders_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_placeholder */
        foreach ($ipe_placeholders as $ipe_placeholder) {
            $this->assertTrue($ipe_placeholder->displayed());
        }

        $ipe_containers_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "paddle-layout-' . $random_layout . '")]';
        $this->waitUntilElementIsDisplayed($ipe_containers_xpath);
        $ipe_containers = $this->elements($this->using('xpath')->value($ipe_containers_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_container */
        foreach ($ipe_containers as $ipe_container) {
            $this->assertTrue($ipe_container->displayed());
        }

        // Save the page so that subsequent tests are not greeted by an alert.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests filtering based on content types.
     *
     * @group facets
     */
    public function testContentTypesFilter()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Create some pages to search for. Publish them immediately.
        // A landing page.
        $landing_page_title = $this->alphanumericTestDataProvider->getValidValue();
        $landing_page_nid = $this->contentCreationService->createLandingPage(null, $landing_page_title);
        $this->publishPage($landing_page_nid);
        // A basic page.
        $basic_page_title = $this->alphanumericTestDataProvider->getValidValue();
        $basic_page_nid = $this->contentCreationService->createBasicPage($basic_page_title);
        $this->publishPage($basic_page_nid);
        // And an overview page.
        $overview_page_title = $this->alphanumericTestDataProvider->getValidValue();
        $overview_page_nid = $this->contentCreationService->createOverviewPage($overview_page_title);
        $this->publishPage($overview_page_nid);

        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();
        // Publish this node too. We don't want to show advanced search pages
        // in the search results, so the test will fail if this page is shown.
        $this->publishPage($nid);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 3 results only.
        $this->assertCount(3, $results);
        // Verify presence of the correct titles.
        $this->assertArrayHasKey($landing_page_title, $results);
        $this->assertArrayHasKey($basic_page_title, $results);
        $this->assertArrayHasKey($overview_page_title, $results);

        // Enable only basic pages to be shown in the results now.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go again to the frontend preview of the search page.
        // We have to use the preview button from now on, because otherwise
        // we will land on the published version of the page which shows all the
        // nodes. Revisions FTW.
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 1 result only.
        $this->assertCount(1, $results);
        // Verify presence of the correct titles.
        $this->assertArrayHasKey($basic_page_title, $results);

        // Enable landing page too now.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('landing_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go again to the frontend preview of the search page.
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 2 results only.
        $this->assertCount(2, $results);
        // Verify presence of the correct titles.
        $this->assertArrayHasKey($landing_page_title, $results);
        $this->assertArrayHasKey($basic_page_title, $results);

        // Last one: enable overview pages.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('paddle_overview_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go again to the frontend preview of the search page.
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendViewPage->checkArrival();

        // Get the available results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // Verify that we have 3 results only.
        $this->assertCount(3, $results);
        // Verify presence of the correct titles.
        $this->assertArrayHasKey($landing_page_title, $results);
        $this->assertArrayHasKey($basic_page_title, $results);
        $this->assertArrayHasKey($overview_page_title, $results);
    }

    /**
     * Tests the search by keyword on advanced search pages.
     *
     * This test will create nodes to verify that searches are using the same
     * fields used by the normal search page, being:
     * - node title;
     * - node summary;
     * - node body;
     * - node taxonomy terms names;
     * - node panes.
     *
     * A few nodes will be populated also with a common word, being the nodes
     * with content in the title, the body and the panes.
     *
     * @group search
     */
    public function testKeywordSearch()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Keep the created nodes data, keying each entry by a label representing
        // the field where the keywords were inserted and having the title of
        // the node and the related keyword as value.
        $nodes = array();

        // Create a common word. We add this word to some pages to verify
        // searches with multiple results.
        $common = $this->alphanumericTestDataProvider->getValidValue();

        // Create a landing page with keywords in the title.
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $title = $keyword . ' ' . $common;
        $nid = $this->contentCreationService->createLandingPage(null, $title);
        $this->publishPage($nid);
        // Save this node info.
        $nodes['title'] = array(
            'title' => $title,
            'keyword' => $keyword,
        );

        // Create a landing page with keywords in the body summary.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createLandingPage(null, $title);
        // Edit the page.
        $this->nodeEditPage->go($nid);
        // Set some text in the teaser.
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->teaserToggleLink->click();
        $this->nodeEditPage->waitUntilTeaserIsDisplayed();
        $this->nodeEditPage->teaser->fill($keyword);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Publish the page.
        $this->publishPage($nid);
        $nodes['summary'] = array(
            'title' => $title,
            'keyword' => $keyword,
        );

        // Create a basic page with text in the body.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        // Set keywords in the body.
        $this->nodeEditPage->go($nid);
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->body->waitUntilReady();
        $this->nodeEditPage->body->setBodyText($keyword . ' ' . $common);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Publish the page.
        $this->publishPage($nid);
        $nodes['body'] = array(
            'title' => $title,
            'keyword' => $keyword,
        );

        // Create an overview page with a custom content pane..
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createOverviewPage($title);
        // Add a custom content pane in a region.
        $this->generalLayoutPage->go($nid);
        $region = $this->generalLayoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $callable = new SerializableClosure(
            function () use ($content_type, $keyword, $common) {
                $content_type->getForm()->body->waitUntilReady();
                $content_type->getForm()->body->setBodyText($keyword . ' ' . $common);
            }
        );
        $region->addPane($content_type, $callable);
        $this->generalLayoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Publish the page.
        $this->publishPage($nid);
        $nodes['panes'] = array(
            'title' => $title,
            'keyword' => $keyword,
        );

        // Create a taxonomy term in the general vocabulary.
        $general_term_title = $this->alphanumericTestDataProvider->getValidValue();
        // Keep the term id of the general tags term as we need it for selection.
        $general_term_tid = $this->taxonomyService->createTerm(
            TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
            $general_term_title
        );
        // And one in the tags.
        $tags_term_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(
            TaxonomyService::TAGS_VOCABULARY_ID,
            $tags_term_title
        );
        // Create a basic page tagged with both the vocabularies.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        // Edit the node.
        $this->nodeEditPage->go($nid);
        // Select the general vocabulary term.
        $this->nodeEditPage->generalVocabularyTermReferenceTree->selectTerm($general_term_tid);
        // And tag with the tags one.
        $this->nodeEditPage->tags->value($tags_term_title);
        $this->moveto($this->nodeEditPage->featuredImage->getWebdriverElement());
        $this->nodeEditPage->tagsAddButton->click();
        $this->nodeEditPage->waitUntilTagIsDisplayed(ucfirst($tags_term_title));
        // Save the page and publish.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);
        $nodes['general'] = array(
            'title' => $title,
            'keyword' => $general_term_title,
        );
        $nodes['tags'] = array(
            'title' => $title,
            'keyword' => $tags_term_title,
        );

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the frontend view.
        $this->frontendViewPage->go($nid);

        // Get the search results.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        // We expect to have 5 nodes.
        $this->assertCount(5, $results);

        // Verify that all our nodes are there.
        foreach ($nodes as $field => $info) {
            $this->assertArrayHasKey($info['title'], $results, "Node with content indexed in $field was not found.");
        }

        // Now launch a search for each keyword.
        foreach ($nodes as $field => $info) {
            $this->frontendViewPage->searchFormPane->form->keywords->fill($info['keyword']);
            $this->frontendViewPage->searchFormPane->form->submit->click();
            $this->frontendViewPage->checkArrival();

            // Verify that only the specific node is being shown.
            $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
            $this->assertCount(1, $results, "More than one node found with keyword {$info['keyword']}, field $field.");
            $this->assertArrayHasKey($info['title'], $results, "Could not find node with content indexed in $field.");
        }

        // Now launch a search on the common keyword.
        $this->frontendViewPage->searchFormPane->form->keywords->fill($common);
        $this->frontendViewPage->searchFormPane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Verify that the 3 expected nodes are found.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(3, $results);
        $this->assertArrayHasKey($nodes['title']['title'], $results);
        $this->assertArrayHasKey($nodes['body']['title'], $results);
        $this->assertArrayHasKey($nodes['panes']['title'], $results);
    }

    /**
     * Make sure no double bodies are shown.
     */
    public function testDoubleBody()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // Add some text in the body.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->waitUntilReady();
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the landing page body is not showing.
        $this->frontendViewPage->go($nid);
        try {
            $this->byCssSelector('.landing-page-body');
            $this->fail('Double body should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }

    /**
     * Tests if the featured image is shown on an advanced search page.
     */
    public function testFeaturedImageShownOnAdvancedSearch()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Create an image atom to test with.
        $atom = $this->assetCreationService->createImage();
        $nid = $this->contentCreationService->createBasicPage();

        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->featuredImage->selectAtom($atom['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $this->publishPage($nid);

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an advanced search node.
        $advanced_nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the frontend view.
        $this->frontendViewPage->go($advanced_nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();

        $this->assertCount(1, $results);
        /** @var SearchResult $result */
        $result = array_shift($results);
        $this->assertNotEmpty($result->featuredImage);
    }

    /**
     * Tests that only current language terms will appear on advanced search edit form.
     */
    public function testTermsLanguageOnSearchPageEditForm()
    {
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        $term_de_title = $this->alphanumericTestDataProvider->getValidValue();
        $term_de_id = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_de_title);
        $this->taxonomyService->changeTermLanguage($term_de_id, 'de');

        $term_nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $term_nl_id = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_nl_title);
        $this->taxonomyService->changeTermLanguage($term_nl_id, 'nl');

        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Enable only basic pages to be shown in the results now.
        $this->nodeEditPage->go($nid);
        $this->assertEquals(1, count($this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows));
        /** @var VocabularyTermsTableRow $row */
        $row = reset($this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows);
        $this->assertEquals($term_nl_title, $row->name);

        $this->contentCreationService->changeNodeLanguage($nid, 'de');
        $this->nodeEditPage->go($nid);
        $this->assertEquals(1, count($this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows));
        /** @var VocabularyTermsTableRow $row */
        $row = reset($this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows);
        $this->assertEquals($term_de_title, $row->name);
    }
}
