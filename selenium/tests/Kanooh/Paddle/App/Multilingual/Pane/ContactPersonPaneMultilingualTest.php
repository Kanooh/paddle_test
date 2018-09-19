<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\Pane\ContactPersonPaneMultilingualTest.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;

/**
* Class ContactPersonPaneMultilingualTest
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
class ContactPersonPaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * Tests the multilingual functionality for contact person panes.
     *
     * @group contactPersonPane
     */
    public function testMultilingual()
    {
        // Delete all contact person pages so we are sure that we get the wanted nodes in the select options.
        $this->cleanUpService->deleteEntities('node', 'contact_person');

        // Create 2 French and 2 Dutch pages.
        $data = array(
            array('lang_code' => 'fr', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'fr', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'nl', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'nl', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
        );

        foreach ($data as $index => $item) {
            $data[$index]['nid'] = $this->contentCreationService->createContactPerson($item['title']);
            $this->contentCreationService->changeNodeLanguage($data[$index]['nid'], $item['lang_code']);
            $this->administrativeNodeViewPage->go($data[$index]['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a Dutch and French landing pages to add the contact person pane to.
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Add a pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $contact_person_pane = new ContactPersonPanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($contact_person_pane);
            $contact_person_pane->getForm()->contactPersonAutocompleteField->fill('node');
            // Get the suggestions and verify it shows only the Dutch nodes.
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $suggestions = $autocomplete->getSuggestions();
            $this->assertCount(2, $suggestions);
            foreach ($data as $item) {
                $this->assertSuggestionPresent($item['title'], $suggestions, $lang_code == $item['lang_code']);
            }

            // Close the modal and save the page so we don't block other tests.
            $modal->close();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
    }
}
