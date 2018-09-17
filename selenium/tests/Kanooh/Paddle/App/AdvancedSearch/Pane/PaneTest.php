<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\Pane\PaneTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\App\AdvancedSearch\AdvancedSearchTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\AdvancedSearch\SearchFormPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearchPanelsContentType;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Class PaneTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends AdvancedSearchTestBase
{
    /**
     * Tests that the search specific panes cannot be edited or deleted.
     */
    public function testPanesToolbarButtons()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(
            TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
            $title
        );

        $nid = $this->contentCreationService->createAdvancedSearchPage();

        // Go to the edit page of the node.
        $this->nodeEditPage->go($nid);
        $rows = $this->nodeEditPage->advancedSearchForm->vocabularyTermsTable->rows;
        // Enable the first row.
        $rows[$tid]->enabled->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();

        $this->adminNodeViewPage->checkArrival();

        $node = node_load($nid);
        $did = $node->panelizer['page_manager']->display->did;
        ctools_include('export');

        $this->layoutPage->go($nid);

        $regions = $this->layoutPage->display->getRegions();
        foreach ($regions as $region) {
            $panes = $region->getPanes();
            $loaded_panes = ctools_export_load_object('panels_pane', 'conditions', array(
                'uuid' => array_keys($panes),
                'did' => $did,
            ));

            foreach ($panes as $uuid => $pane) {
                // We need to use the subtype because the Ctools export mechanism
                // will put the status "Normal" in the type property.
                if ($loaded_panes[$uuid]->subtype == 'advanced_search_results') {
                    $pane->toolbar->checkButtonsNotPresent(array(
                      'Delete',
                      'Edit',
                      'PaddleStyle'
                    ));
                    $pane->toolbar->checkButtons(array('DragHandle'));
                } else {
                    $pane->toolbar->checkButtonsNotPresent(array(
                      'Delete',
                      'Edit'
                    ));
                    $pane->toolbar->checkButtons(array(
                      'DragHandle',
                      'PaddleStyle'
                    ));
                }
            }
        }

        // Save the page to end the test.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests the autocomplete functionality for the advanced search field pane.
     */
    public function testAdvancedSearchPaneAutocomplete()
    {
        // Create a prefix common to all pages.
        $prefix = $this->alphanumericTestDataProvider->getValidValue() . ' ';

        // Create an advanced search page with no revisions. This page will have
        // no displays.
        $no_display_title = $prefix . $this->alphanumericTestDataProvider->getValidValue() . ' no display';
        $no_display_nid = $this->contentCreationService->createAdvancedSearchPage($no_display_title);

        // Create an advanced search page with a revision. This will create a
        // panelizer display for it.
        $with_display_title = $prefix . $this->alphanumericTestDataProvider->getValidValue() . ' display';
        $with_display_nid = $this->contentCreationService->createAdvancedSearchPage($with_display_title);

        // Create another page with the search form disabled.
        $disabled_title = $prefix . $this->alphanumericTestDataProvider->getValidValue() . ' disabled';
        $disabled_nid = $this->contentCreationService->createAdvancedSearchPage($disabled_title);
        // Edit the page and disable the search form.
        $this->nodeEditPage->go($disabled_nid);
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Create another page with the search form enabled in the published
        // revision and disabled in the latest draft.
        $enabled_live_title = $prefix . $this->alphanumericTestDataProvider->getValidValue() . 'enabled live';
        $enabled_live_nid = $this->contentCreationService->createAdvancedSearchPage($enabled_live_title);
        $this->publishPage($enabled_live_nid);
        // Now edit the page and disable the search form.
        $this->nodeEditPage->go($enabled_live_nid);
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();

        // Create a basic page to hold the pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the layout page.
        $this->generalLayoutPage->go($nid);
        // Get a random region to insert the pane in.
        $region = $this->generalLayoutPage->display->getRandomRegion();

        // Create an instance of the search form pane.
        $content_type = new AdvancedSearchPanelsContentType($this);

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();

        // Select the pane type in the modal dialog.
        $modal->selectContentType($content_type);

        // Insert the shared prefix into the autocomplete. This will avoid noise
        // from other tests.
        $content_type->getForm()->autocompleteField->fill(trim($prefix));
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();

        $suggestions = $autocomplete->getSuggestions();
        $this->assertContains("$no_display_title (node/$no_display_nid)", $suggestions);
        $this->assertContains("$with_display_title (node/$with_display_nid)", $suggestions);
        $this->assertNotContains("$disabled_title (node/$disabled_nid)", $suggestions);
        $this->assertContains("$enabled_live_title (node/$enabled_live_nid)", $suggestions);
        $this->assertCount(3, $suggestions);

        // Pick the first suggestion to allow closing the modal.
        $autocomplete->pickSuggestionByPosition();
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the page to finish the test.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests the rendering of the advanced search field pane.
     */
    public function testAdvancedSearchPaneRendering()
    {
        // Create an advanced search page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createAdvancedSearchPage($title);

        // Create a basic page to hold the pane.
        $basic_nid = $this->contentCreationService->createBasicPage();

        // Go to the layout page.
        $this->generalLayoutPage->go($basic_nid);
        // Get a random region to insert the pane in.
        $region = $this->generalLayoutPage->display->getRandomRegion();

        // Create an instance of the search form pane.
        $content_type = new AdvancedSearchPanelsContentType($this);
        // Prepare the callback to configure the pane.
        $test_case = $this;
        $callback = new SerializableClosure(
            function () use ($test_case, $content_type, $title) {
                $content_type->getForm()->autocompleteField->fill($title);
                $autocomplete = new AutoComplete($test_case);
                $autocomplete->waitUntilDisplayed();
                $autocomplete->pickSuggestionByPosition();
            }
        );
        // Add the pane to the selected region.
        $pane = $region->addPane($content_type, $callback);

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view of the basic page as logged in user.
        $this->generalFrontendViewPage->go($basic_nid);

        // Find our pane.
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        // Verify that the button has the default label.
        $this->assertEquals('Search', $search_form_pane->form->submit->attribute('value'));

        // Open the page as anonymous user. To do so, publish the basic page.
        $this->publishPage($basic_nid);
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);

        // Verify that the pane is not rendered for anonymous users.
        $this->assertSearchFormPaneNotRendered(
            $pane->getUuid(),
            'The search form pane should not be rendered for anonymous users.'
        );

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Edit the advanced search page and set a custom button text.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->uncheck();
        $custom_text = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->advancedSearchForm->customSearchButtonText->fill($custom_text);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        // Publish the page too.
        $this->publishPage($nid);

        // Go to the frontend view of the basic page.
        $this->generalFrontendViewPage->go($basic_nid);

        // Find our pane.
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        // Verify that the button has the custom label.
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Open the page as anonymous user.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);

        // Find again our pane and verify its label. This will automatically
        // verify that the pane is correctly shown to anonymous users when the
        // related advanced search page is published.
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        // Verify that the button has the custom label.
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Edit the page and change again the button text. Do not publish
        // this revision.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->useDefaultSearchButtonText->uncheck();
        $new_custom_text = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->advancedSearchForm->customSearchButtonText->fill($new_custom_text);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view of the basic page.
        $this->generalFrontendViewPage->go($basic_nid);

        // Find our pane.
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        // Verify that the button is showing the custom label of the published
        // version.
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Do the same assertion for the anonymous user.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Edit the advanced search page and disable the search form. Do not
        // publish, so we can verify that the published version is still showing.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view and verify that the pane is still shown.
        $this->generalFrontendViewPage->go($basic_nid);
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Do the same assertion for the anonymous user.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        $this->assertEquals($custom_text, $search_form_pane->form->submit->attribute('value'));

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Publish the latest version of the advanced search page.
        $this->publishPage($nid);

        // Go to the frontend view and verify that the pane is not shown anymore.
        $this->generalFrontendViewPage->go($basic_nid);
        $this->assertSearchFormPaneNotRendered(
            $pane->getUuid(),
            'The search form pane should not be rendered when disabled on published revision.'
        );

        // Do the same for anonymous users.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);
        $this->assertSearchFormPaneNotRendered(
            $pane->getUuid(),
            'The search form pane should not be rendered when disabled on published revision.'
        );

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Re-enable the search form, but don't publish the revision.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->advancedSearchForm->enableSearchForm->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view and verify that the pane is still not shown.
        $this->generalFrontendViewPage->go($basic_nid);
        $this->assertSearchFormPaneNotRendered(
            $pane->getUuid(),
            'The search form pane should not be rendered when disabled on published revision.'
        );

        // Do the same for anonymous users.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);
        $this->assertSearchFormPaneNotRendered(
            $pane->getUuid(),
            'The search form pane should not be rendered when disabled on published revision.'
        );

        // Log in back as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Publish the latest advanced search revision.
        $this->publishPage($nid);

        // Go to the frontend view of the basic page and verify that the pane
        // is shown with the default label.
        $this->generalFrontendViewPage->go($basic_nid);
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        $this->assertEquals('Search', $search_form_pane->form->submit->attribute('value'));

        // Do the same assertion for the anonymous user.
        $this->userSessionService->logout();
        $this->generalFrontendViewPage->go($basic_nid);
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        $this->assertEquals('Search', $search_form_pane->form->submit->attribute('value'));
    }

    /**
     * Tests searches launched from an advanced search pane.
     */
    public function testAdvancedSearchPaneSearch()
    {
        // Create a common keyword to share between nodes.
        $common = $this->alphanumericTestDataProvider->getValidValue();

        // Create a basic page with the common keyword.
        $basic_title = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $common;
        $basic_nid = $this->contentCreationService->createBasicPage($basic_title);
        $this->publishPage($basic_nid);
        // And one without.
        $basic_hidden_nid = $this->contentCreationService->createBasicPage();
        $this->publishPage($basic_hidden_nid);

        // Create two landing pages with the same principle as above.
        $landing_title = $this->alphanumericTestDataProvider->getValidValue() . ' ' . $common;
        $landing_nid = $this->contentCreationService->createLandingPage(null, $landing_title);
        $this->publishPage($landing_nid);
        // And one without.
        $landing_hidden_nid = $this->contentCreationService->createLandingPage();
        $this->publishPage($landing_hidden_nid);

        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create an advanced search page.
        $advanced_first_title = $this->alphanumericTestDataProvider->getValidValue();
        $advanced_first_nid = $this->contentCreationService->createAdvancedSearchPage($advanced_first_title);
        // Edit the node and configure it to show only basic pages.
        $this->nodeEditPage->go($advanced_first_nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('basic_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Create another advanced search page.
        $advanced_second_title = $this->alphanumericTestDataProvider->getValidValue();
        $advanced_second_nid = $this->contentCreationService->createAdvancedSearchPage($advanced_second_title);
        // Edit the node and configure it to show only landing pages.
        $this->nodeEditPage->go($advanced_second_nid);
        $this->nodeEditPage->advancedSearchForm->contentTypes->getByValue('landing_page')->check();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Create a basic page to hold the advanced search panes.
        $holder_nid = $this->contentCreationService->createBasicPage();
        // Go to the layout page.
        $this->generalLayoutPage->go($holder_nid);
        // Get a random region to insert the pane in.
        $region = $this->generalLayoutPage->display->getRandomRegion();

        // Keep the created pane instances.
        $panes = array();

        $test_case = $this;
        foreach (array($advanced_first_title, $advanced_second_title) as $title) {
            // Create an instance of the search form pane.
            $content_type = new AdvancedSearchPanelsContentType($this);
            // Prepare the callback to configure the pane.
            $callback = new SerializableClosure(
                function () use ($test_case, $content_type, $title) {
                    $content_type->getForm()->autocompleteField->fill($title);
                    $autocomplete = new AutoComplete($test_case);
                    $autocomplete->waitUntilDisplayed();
                    $autocomplete->pickSuggestionByPosition();
                }
            );
            // Add the pane to the selected region.
            $pane = $region->addPane($content_type, $callback);

            // Save the created pane instance.
            $panes[$title] = $pane;
        }

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view of the basic page.
        $this->generalFrontendViewPage->go($holder_nid);

        // Find the first pane.
        $search_form_pane = new SearchFormPane($this, $panes[$advanced_first_title]->getUuid());
        // Launch the search with the common keyword.
        $search_form_pane->form->keywords->fill($common);
        $search_form_pane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Verify that we have been redirected to the first advanced search page.
        $expected_arguments = array($advanced_first_nid);
        $this->assertEquals($expected_arguments, $this->frontendViewPage->getPathArguments());

        // Verify that the search results are correct.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($basic_title, $results);

        // Go back to the frontend view of the basic page.
        $this->generalFrontendViewPage->go($holder_nid);

        // Find the second pane.
        $search_form_pane = new SearchFormPane($this, $panes[$advanced_second_title]->getUuid());
        // Launch the search with the common keyword.
        $search_form_pane->form->keywords->fill($common);
        $search_form_pane->form->submit->click();
        $this->frontendViewPage->checkArrival();

        // Verify that we have been redirected to the second advanced search page.
        $expected_arguments = array($advanced_second_nid);
        $this->assertEquals($expected_arguments, $this->frontendViewPage->getPathArguments());

        // Verify that the search results are correct.
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();
        $this->assertCount(1, $results);
        $this->assertArrayHasKey($landing_title, $results);
    }

    /**
     * Tests searches launched from an advanced search pane.
     *
     * Checks whether the default filter remains as query parameter. Doesn't
     * have to verify the correct functioning of the filter itself on the
     * advanced search page itself. There are other tests for that.
     */
    public function testAdvancedSearchPaneDefaultFilter()
    {
        $adv_nid = $this->contentCreationService->createAdvancedSearchPage();
        $bp_nid = $this->contentCreationService->createBasicPage();
        $default_filter = 'f%5B0%5D=field_paddle_cirro_methodology%3A0';

        // Go to the layout page.
        $this->generalLayoutPage->go($bp_nid);
        // Get a random region to insert the pane in.
        $region = $this->generalLayoutPage->display->getRandomRegion();
        $test_case = $this;
        // Create an instance of the search form pane.
        $content_type = new AdvancedSearchPanelsContentType($this);
        // Configure the pane.
        $callback = new SerializableClosure(
            function () use ($test_case, $content_type, $adv_nid, $default_filter) {
                $content_type->getForm()->autocompleteField->fill('node/' . $adv_nid);
                $autocomplete = new AutoComplete($test_case);
                $autocomplete->waitUntilDisplayed();
                $autocomplete->pickSuggestionByPosition();
                $content_type->getForm()->defaultFilter->fill($default_filter);
            }
        );
        // Add the pane to the selected region.
        $pane = $region->addPane($content_type, $callback);
        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Go to the frontend view of the basic page.
        $this->generalFrontendViewPage->go($bp_nid);
        // Find the pane.
        $search_form_pane = new SearchFormPane($this, $pane->getUuid());
        // Launch the search.
        $search_form_pane->form->submit->click();
        // Wait until we arrive at the advanced search page.
        $this->frontendViewPage->checkArrival();

        // Get the query string from the current URL.
        $query_string = $this->execute(
            array(
                'script' => 'return window.location.search',
                'args' => array(),
            )
        );

        $this->assertContains($default_filter, $query_string);
    }

    /**
     * Asserts that the search form pane is not rendered in the page.
     *
     * @param string $pane_uuid
     *   The uuid of the search form pane we are trying to find.
     * @param string $message
     *   The message to show on failure.
     */
    protected function assertSearchFormPaneNotRendered($pane_uuid, $message = '')
    {
        try {
            $search_form_pane = new SearchFormPane($this, $pane_uuid);
            // Access an element to throw an exception.
            $search_form_pane->form->submit->click();
            $this->fail($message);
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }
}
