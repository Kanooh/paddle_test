<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\Pane\PollPaneMultilingualTest.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;

/**
* Class PollPaneMultilingualTest
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
class PollPaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Enable the app if it is not enabled yet.
        $this->appService->enableApp(new Poll);
    }

    /**
     * Tests the multilingual functionality for poll panes.
     *
     * @group contactPersonPane
     */
    public function testMultilingual()
    {
        // Delete all poll pages so we are sure that we get the wanted nodes in the select options.
        $this->cleanUpService->deleteEntities('node', 'poll');

        // Create 2 French and 2 Dutch pages.
        $data = array(
            array('lang_code' => 'fr', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'fr', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'nl', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
            array('lang_code' => 'nl', 'title' => $this->alphanumericTestDataProvider->getValidValue(), 'nid' => 0),
        );

        foreach ($data as $index => $item) {
            $data[$index]['nid'] = $this->contentCreationService->createPollPage($item['title']);
            $this->contentCreationService->changeNodeLanguage($data[$index]['nid'], $item['lang_code']);
            $this->administrativeNodeViewPage->go($data[$index]['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a Dutch and French landing pages to add the poll pane to.
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Add a pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $poll_pane = new PollPanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($poll_pane);
            $poll_pane->getForm()->autocompleteField->fill('node');
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
