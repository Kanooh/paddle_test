<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Class ConfigurationTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationAdvancedSearchTest extends AdvancedSearchTestBase
{

    /**
     * Tests configuration for the content types field.
     */
    public function testContentTypesConfiguration()
    {
        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);

        // Verify that no content types are selected by default.
        $this->assertEmpty($this->nodeEditPage->advancedSearchForm->contentTypes->getChecked());
        // Verify also that the advanced search page itself is not shown.
        $this->assertArrayNotHasKey(
            'paddle_advanced_search_page',
            $this->nodeEditPage->advancedSearchForm->contentTypes->getAll()
        );

        // Check basic page and save.
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again and verify that the configuration is kept.
        $this->nodeEditPage->go($nid);
        $this->assertEquals(
            array('basic_page'),
            array_keys($this->nodeEditPage->advancedSearchForm->contentTypes->getChecked())
        );

        // Check another one.
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('landing_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again and verify that the configuration is correct.
        $this->nodeEditPage->go($nid);
        $this->assertEquals(
            array('basic_page', 'landing_page'),
            array_keys($this->nodeEditPage->advancedSearchForm->contentTypes->getChecked())
        );
    }

    /**
     * Tests configuration for the default sort fields.
     */
    public function testDefaultSortConfiguration()
    {
        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);

        // Verify that the default sorting is "Relevance - Descending".
        $this->assertEquals('search_api_relevance', $this->nodeEditPage->advancedSearchForm->defaultSortOption->getSelected()->getValue());
        $this->assertEquals('DESC', $this->nodeEditPage->advancedSearchForm->defaultSortOrder->getSelected()->getValue());

        // Select publication date and save.
        $this->nodeEditPage->advancedSearchForm->defaultSortOption->publication_date->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again and verify that the configuration is kept.
        $this->nodeEditPage->go($nid);
        $this->assertEquals('publication_date', $this->nodeEditPage->advancedSearchForm->defaultSortOption->getSelected()->getValue());
        $this->assertEquals('DESC', $this->nodeEditPage->advancedSearchForm->defaultSortOrder->getSelected()->getValue());
    }

    /**
     * Tests the configuration for the vocabulary term filters.
     */
    public function testVocabularyFilterConfiguration()
    {
        // we first need to delete all existing terms.
        $this->cleanUpService->deleteEntities('taxonomy_term');

        // Prepare a shared prefix between terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue();
        // Create three root terms with some children.
        $terms = $this->taxonomyService
            ->createHierarchicalStructure(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, 2, 3, 0, $prefix);

        // Create a node tagged with the 3 child terms. This is needed to
        // show the facet itself and verify its presence.
        $this->createNodeForTerms(array(
            $terms[1][1]['#tid'],
            $terms[2][1]['#tid'],
            $terms[3][1]['#tid'],
        ));

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);

        // Verify that only root terms rows are shown.
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        $this->assertSameSize($terms, $rows);

        foreach ($terms as $key => $term_info) {
            $this->assertArrayHasKey($term_info['#tid'], $rows);
            $row = $rows[$term_info['#tid']];
            // Verify that the expected name is there.
            $term_name = $prefix . $key;
            $this->assertEquals($term_name, $row->name);
            // Verify that the checkbox is disabled by default.
            $this->assertFalse($row->enabled->isChecked(), "$term_name should not be checked by default.");
            // Verify that the mode radios are disabled and they default to
            // list mode.
            $this->assertFalse($row->mode->list->isEnabled(), "List radio should be disabled for $term_name.");
            $this->assertFalse($row->mode->dropdown->isEnabled(), "Dropdown radio should be disabled for $term_name.");
            $this->assertFalse($row->mode->hidden->isEnabled(), "Hidden radio should be disabled for $term_name.");
            $this->assertTrue($row->mode->list->isSelected(), "List radio should be checked by default for $term_name");
        }

        // Enable the first row.
        $rows[$terms[1]['#tid']]->enabled->check();
        // Verify that the mode radios are enabled now for this row. Check just
        // for the list one, they all share the same settings.
        $this->assertTrue($rows[$terms[1]['#tid']]->mode->list->isEnabled());
        // The other rows should stay disabled.
        $this->assertFalse($rows[$terms[2]['#tid']]->mode->list->isEnabled());
        $this->assertFalse($rows[$terms[3]['#tid']]->mode->list->isEnabled());

        // Enable also the second row and check again.
        $rows[$terms[2]['#tid']]->enabled->check();
        $this->assertTrue($rows[$terms[1]['#tid']]->mode->list->isEnabled());
        $this->assertTrue($rows[$terms[2]['#tid']]->mode->list->isEnabled());
        $this->assertFalse($rows[$terms[3]['#tid']]->mode->list->isEnabled());

        // Set some modes and save the page.
        $rows[$terms[1]['#tid']]->mode->hidden->select();
        $rows[$terms[2]['#tid']]->mode->dropdown->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the layout page.
        $this->layoutPage->go($nid);
        // Get the left region.
        $left_region = $this->layoutPage->display->region('left')->getWebdriverElement();
        // Verify that only the second pane is rendered.
        $this->assertTextPresent($prefix . '2', $left_region);
        // Verify that the hidden pane is not rendered.
        $this->assertTextNotPresent($prefix . '1');
        // And neither the third pane is.
        $this->assertTextNotPresent($prefix . '3');

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page and verify that the configuration has been kept.
        $this->nodeEditPage->go($nid);
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        $this->assertTrue($rows[$terms[1]['#tid']]->enabled->isChecked());
        $this->assertTrue($rows[$terms[1]['#tid']]->mode->hidden->isSelected());
        $this->assertTrue($rows[$terms[2]['#tid']]->enabled->isChecked());
        $this->assertTrue($rows[$terms[2]['#tid']]->mode->dropdown->isSelected());
        $this->assertFalse($rows[$terms[3]['#tid']]->enabled->isChecked());
        $this->assertFalse($rows[$terms[3]['#tid']]->mode->list->isEnabled());

        // Disable the first row and change again the configuration for the
        // second row.
        $rows[$terms[1]['#tid']]->enabled->uncheck();
        $rows[$terms[2]['#tid']]->mode->list->select();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go again to the layout page.
        $this->layoutPage->go($nid);
        // Get the left region.
        $left_region = $this->layoutPage->display->region('left')->getWebdriverElement();
        // Verify that the second term pane is rendered in there.
        $this->assertTextPresent($prefix . '2', $left_region);
        // And the other two are not.
        $this->assertTextNotPresent($prefix . '1');
        $this->assertTextNotPresent($prefix . '3');

        // Change the layout of the page.
        $curr_layout = $this->layoutPage->display->getCurrentLayoutId();
        $allowed_layouts = $this->layoutPage->display->getSupportedLayouts();
        // Unset the current layout.
        unset($allowed_layouts[$curr_layout]);
        $random_layout = array_rand($allowed_layouts);
        $this->layoutPage->changeLayout($random_layout);

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the node again.
        $this->nodeEditPage->go($nid);
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        // The first row should be disabled.
        $this->assertFalse($rows[$terms[1]['#tid']]->enabled->isChecked());
        // And the default selected radio should be again list, and with a
        // disabled status.
        $this->assertTrue($rows[$terms[1]['#tid']]->mode->list->isSelected());
        $this->assertFalse($rows[$terms[1]['#tid']]->mode->list->isEnabled());
        // Verify that the configuration for the second row was kept.
        $this->assertTrue($rows[$terms[2]['#tid']]->enabled->isChecked());
        $this->assertTrue($rows[$terms[2]['#tid']]->mode->list->isSelected());
        // And that the third row is still disabled.
        $this->assertFalse($rows[$terms[3]['#tid']]->enabled->isChecked());
        $this->assertFalse($rows[$terms[3]['#tid']]->mode->list->isEnabled());

        // Enable the third row and save the page.
        $rows[$terms[3]['#tid']]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go again to the layout page.
        $this->layoutPage->go($nid);
        // Verify that the second and third term panes are rendered somewhere
        // in the page.
        $this->assertTextPresent($prefix . '2');
        $this->assertTextPresent($prefix . '3');
        // And that the first one is not.
        $this->assertTextNotPresent($prefix . '1');

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the node again.
        $this->nodeEditPage->go($nid);
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        // Disable all the rows.
        foreach ($rows as $row) {
            $row->enabled->uncheck();
        }
        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the node again.
        $this->nodeEditPage->go($nid);
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        // Verify that also this configuration has been saved correctly.
        $this->assertFalse($rows[$terms[1]['#tid']]->enabled->isChecked());
        $this->assertFalse($rows[$terms[2]['#tid']]->enabled->isChecked());
        $this->assertFalse($rows[$terms[3]['#tid']]->enabled->isChecked());

        // Save the page to end the test.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests the search form pane configuration.
     *
     * @group search
     */
    public function testSearchFormConfiguration()
    {
        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);
        // Verify that the search form pane is shown and has the default label.
        $this->assertEquals('Search', $this->frontendViewPage->searchFormPane->form->submit->attribute('value'));
        // Save the pane uuid for later.
        $form_pane_uuid = $this->frontendViewPage->searchFormPane->getUuid();
        // Do the same for the search results pane.
        $results_pane_uuid = $this->frontendViewPage->searchResultsPane->getUuid();

        // Verify that the pane is also being displayed in the layout page.
        $this->layoutPage->go($nid);
        // The pane has to be present in the right region by default.
        $this->assertArrayHasKey($form_pane_uuid, $this->layoutPage->display->region('right')->getPanes());

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);

        // Verify that the search form is enabled by default.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->enableSearchForm->isChecked());
        // And that the default text is used by default.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isDisplayed());
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isChecked());
        // And that the custom text field is not shown.
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());

        // Disable the default text.
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->uncheck();
        // Verify that now the custom text field is shown.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());
        // And its value is the default one.
        $this->assertEquals('Search', $this->nodeEditPage->advancedSearchForm->customSearchButtonText->getContent());
        // Enable the default text again.
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->check();
        // Verify that now the custom text field is hidden.
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());
        // Add a custom text.
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->uncheck();
        $custom_text = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->advancedSearchForm->customSearchButtonText->fill($custom_text);

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and verify that the button has the custom text.
        $this->frontendViewPage->go($nid);
        $this->assertEquals($custom_text, $this->frontendViewPage->searchFormPane->form->submit->attribute('value'));

        // Verify that nothing changed in the layout page.
        $this->layoutPage->go($nid);
        $this->assertArrayHasKey($form_pane_uuid, $this->layoutPage->display->region('right')->getPanes());

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again.
        $this->nodeEditPage->go($nid);
        // Verify that the configuration is kept.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->enableSearchForm->isChecked());
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isChecked());
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());
        $this->assertEquals(
            $custom_text,
            $this->nodeEditPage->advancedSearchForm->customSearchButtonText->getContent()
        );

        // Disable the search form at all.
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->uncheck();
        // Verify that the other two elements get hidden.
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isDisplayed());
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend and verify that the pane is not rendered.
        $this->frontendViewPage->go($nid);
        try {
            $this->frontendViewPage->searchFormPane;
            $this->fail('The search form pane should not be rendered.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Verify that the pane has been removed from the layout page.
        $this->layoutPage->go($nid);
        // The right region has to have only one pane.
        $panes = $this->layoutPage->display->region('right')->getPanes();
        $this->assertCount(1, $panes);
        // And that pane should be the search results.
        $this->assertArrayHasKey($results_pane_uuid, $panes);
        // No panes in the left region.
        $this->assertEmpty($this->layoutPage->display->region('left')->getPanes());

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again.
        $this->nodeEditPage->go($nid);
        // Verify that the search form is marked as disabled.
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->enableSearchForm->isChecked());
        // And the other two related form elements are not visible.
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isDisplayed());
        $this->assertFalse($this->nodeEditPage->advancedSearchForm->customSearchButtonText->isDisplayed());

        // The default pane was deleted from the node display when we disabled
        // it. So let's test that the pane is added again properly.
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->check();
        // The default text checkbox should be visible and checked now.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isDisplayed());
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isChecked());
        // Verify that the custom text was resetted to a default value.
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->uncheck();
        $this->assertEquals('Search', $this->nodeEditPage->advancedSearchForm->customSearchButtonText->getContent());
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->check();

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);
        // Verify that the search form pane is shown and has the default label.
        $this->assertEquals('Search', $this->frontendViewPage->searchFormPane->form->submit->attribute('value'));
        // Save again its uuid.
        $form_pane_uuid = $this->frontendViewPage->searchFormPane->getUuid();

        // Verify that the pane has been added back to the layout page.
        $this->layoutPage->go($nid);
        // The pane has been added to the left region now.
        $panes = $this->layoutPage->display->region('left')->getPanes();
        $this->assertCount(1, $panes);
        $this->assertArrayHasKey($form_pane_uuid, $panes);
        // Verify that on the right region we have only the search results pane.
        $panes = $this->layoutPage->display->region('right')->getPanes();
        $this->assertCount(1, $panes);
        $this->assertArrayHasKey($results_pane_uuid, $panes);

        // Save the page to go back to the admin view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page again.
        $this->nodeEditPage->go($nid);
        // Verify that the configuration is kept.
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->enableSearchForm->isChecked());
        $this->assertTrue($this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->isChecked());

        // Make sure the alert box doesn't pop.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    public function testAdvancedSearchDisplayResultCount()
    {
        // Create 4 basic pages and publish them.
        for ($i = 0; $i < 4; $i++) {
            $basic_page_nid = $this->contentCreationService->createBasicPage();
            $this->publishPage($basic_page_nid);
        }
        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Enable basic pages to be shown in the results.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');
        // Go to the frontend view of the node (advanced search).
        $this->frontendViewPage->go($nid);

        // Check if the result count is not shown by default.
        try {
            $this->frontendViewPage->searchResultCount;
            $this->fail('The search result count should not be rendered.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Enable the result count.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->displayResultCount->check();

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);

        $this->frontendViewPage->go($nid);

        try {
            $this->frontendViewPage->searchResultCount;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail('The search result count should be rendered, but is not.');
        }
    }

    /**
     * Tests the pagination configuration of the Advanced Search page.
     */
    public function testAdvancedSearchPaginationConfigurations()
    {
        // Create 21 basic pages and publish them.
        for ($i = 0; $i < 21; $i++) {
            $basic_page_nid = $this->contentCreationService->createBasicPage();
            $this->publishPage($basic_page_nid);
        }

        // Create an advanced search node.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Enable basic pages to be shown in the results.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);

        // Verify that the pager is shown by default below the search results.
        $this->assertTrue($this->frontendViewPage->isPagerShown('Bottom'));
        // Verify that the pager is not shown at the top of the search
        // results.
        $this->assertFalse($this->frontendViewPage->isPagerShown('Top'));

        // Edit the page and tick off the Top pager.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->pagerTop->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);

        // Verify that the pager is shown by both at the top as at the bottom
        // of the search results.
        $this->assertTrue($this->frontendViewPage->isPagerShown('Bottom'));
        $this->assertTrue($this->frontendViewPage->isPagerShown('Top'));

        // Edit the page and uncheck the Bottom pager.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->pagerBottom->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->publishPage($nid);

        // Go to the frontend view of the node.
        $this->frontendViewPage->go($nid);

        // Verify that the pager is only shown at the top.
        $this->assertFalse($this->frontendViewPage->isPagerShown('Bottom'));
        $this->assertTrue($this->frontendViewPage->isPagerShown('Top'));

        // Remove all the basic pages and advanced search page(s), to keep compatibility with other tests.
        $this->cleanUpService->deleteEntities('node', 'basic_page');
        $this->cleanUpService->deleteEntities('node', 'paddle_advanced_search_page');
    }
}
