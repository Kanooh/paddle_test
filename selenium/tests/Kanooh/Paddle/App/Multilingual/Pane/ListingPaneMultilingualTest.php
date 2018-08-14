<?php
/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\Pane\ListingPaneMultilingualTest.
 */

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\Paddle\Pages\Element\Pane\ListingPane;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Class ListingPaneMultilingualTest
 * @package Kanooh\Paddle\App\Multilingual\Pane
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var OverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->nodeEditPage = new NodeEditPage($this);
        $this->taxonomyOverviewPage = new OverviewPage($this);
    }

    /**
     * Tests the multilingual functionality for listing panes.
     *
     * @group listingPane
     */
    public function testMultilingual()
    {
        // Create French and Dutch pages.
        $data = array(
            'fr' => array('title' => $this->alphanumericTestDataProvider->getValidValue()),
            'nl' => array('title' => $this->alphanumericTestDataProvider->getValidValue()),
        );

        foreach ($data as $lang_code => $item) {
            $data[$lang_code]['nid'] = $this->contentCreationService->createBasicPage($item['title']);
            $this->contentCreationService->changeNodeLanguage($data[$lang_code]['nid'], $lang_code);
            $this->administrativeNodeViewPage->go($data[$lang_code]['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Add a Listing pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $listing_pane = new ListingPanelsContentType($this);
            $pane = $region->addPane($listing_pane);
            $uuid = $pane->getUuid();

            // Save the page to make sure we get the pane.
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            $listing_pane = new ListingPane($this, $uuid, '//*[@data-pane-uuid = "' . $uuid . '"]');

            // Verify that only the node with the same language is shown.
            foreach ($data as $code => $item) {
                $this->assertEquals($lang_code == $code, $listing_pane->nodeExistsInListing($item['nid']));
            }
        }
    }

    /**
     * Tests if the correct tags/terms are shown in the autocomplete.
     */
    public function testMultilingualTaxonomy()
    {
        // Delete the existing taxonomy_term entities.
        $this->cleanUpService->deleteEntities('taxonomy_term');
        $taxonomy_service = new TaxonomyService();

        // Get the id's of the 2 vocabularies for easier reference.
        $tags_vid = $taxonomy_service::TAGS_VOCABULARY_ID;
        $terms_vid = $taxonomy_service::GENERAL_TAGS_VOCABULARY_ID;

        // Create a node.
        $nid = $this->contentCreationService->createLandingPage();

        // Create data for terms and tags in Dutch and English.
        // The term will contain the same data so we can test if the functionality
        // works when translations of the tag are basically the same.
        $data = array(
            'nl' => array(
                $tags_vid => 'testtag' . $this->alphanumericTestDataProvider->getValidValue(),
                $terms_vid => 'testterm',
            ),
            'en' => array(
                $tags_vid => 'testtag' . $this->alphanumericTestDataProvider->getValidValue(),
                $terms_vid => 'testterm',
            )
        );

        // Create all tags/terms for each language as previously set.
        foreach ($data as $lang => $terms) {
            foreach ($terms as $type => $title) {
                $tid = $taxonomy_service->createTerm($type, $title);
                $taxonomy_service->changeTermLanguage($tid, $lang);
            }
        }

        // Loop over the created terms and verify that the autocomplete in the
        // listing pane shows the correct terms/tags.
        foreach ($data as $lang => $terms) {
            // Get the other language for easy reference.
            $other_lang = $lang == 'nl' ? 'en' : 'nl';

            // Go to the layout page.
            $this->landingPagePanelsPage->go($nid);
            // Add a listing pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $listing_pane = new ListingPanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($listing_pane);
            // Do the test for the tags.
            $listing_pane->filterTags->fill('testtag');
            // Get the suggestions and verify it shows only the tags with the
            // same language as page language.
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $suggestions = $autocomplete->getSuggestions();
            $this->assertCount(1, $suggestions);
            $this->assertSuggestionPresent($data[$lang][$tags_vid], $suggestions, true);
            $this->assertSuggestionPresent($data[$other_lang][$tags_vid], $suggestions, false);

            // Do the test for the terms.
            $listing_pane->filterGeneralTags->fill('testterm');
            // Get the suggestions and verify it shows only the Dutch terms.
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $suggestions = $autocomplete->getSuggestions();
            $this->assertCount(1, $suggestions);
            $this->assertSuggestionPresent($data[$lang][$terms_vid], $suggestions, true);

            // Save the modal and make sure it succeeds.
            $modal->submit();
            $this->landingPagePanelsPage->checkArrival();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->contentCreationService->changeNodeLanguage($nid, $other_lang);
        }
    }

    /**
     * Tests duplicate terms per language.
     */
    public function testDuplicateTermsPerLanguage()
    {
        // Delete the existing taxonomy_term & node entities.
        $this->cleanUpService->deleteEntities('taxonomy_term');
        $this->cleanUpService->deleteEntities('node', 'basic_page');
        $this->taxonomyOverviewPage = new OverviewPage($this);

        $data = array(
            'fr' => array('title' => $this->alphanumericTestDataProvider->getValidValue()),
            'nl' => array('title' => $this->alphanumericTestDataProvider->getValidValue()),
        );

        $term = array(
          'name' => 'IAmTheSame',
        );

        // For each language, create a taxonomy term and add it to a node which contains that language.
        foreach ($data as $lang_code => $item) {
            $this->taxonomyOverviewPage->go(OverviewPage::GENERAL_TAGS_VOCABULARY_ID);


            $this->taxonomyOverviewPage->languageSwitcher->switchLanguage($lang_code);
            $this->taxonomyOverviewPage->checkArrival();

            $data[$lang_code]['tid'] = $this->taxonomyOverviewPage->createTerm($term);
            $data[$lang_code]['nid'] = $this->contentCreationService->createBasicPage($item['title']);

            $this->contentCreationService->changeNodeLanguage($data[$lang_code]['nid'], $lang_code);

            $this->nodeEditPage->go($data[$lang_code]['nid']);
            $this->nodeEditPage->generalVocabularyTermReferenceTree->expandAllTerms();
            $this->nodeEditPage->generalVocabularyTermReferenceTree->getTermById($data[$lang_code]['tid'])->select();

            $this->nodeEditPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // This foreach need to be cast separately because both terms need to exist already.
        foreach ($data as $lang_code => $item) {
            // Add a landing page in the current language to add a listing pane to.
            $data[$lang_code]['landing_nid'] = $this->contentCreationService->createLandingPage();
            $this->contentCreationService->changeNodeLanguage($data[$lang_code]['landing_nid'], $lang_code);

            $this->landingPagePanelsPage->go($data[$lang_code]['landing_nid']);

            // Add a listing pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $listing_pane = new ListingPanelsContentType($this);

            $callable = new SerializableClosure(
                function () use ($listing_pane) {
                    // Fill the general tag field.
                    $listing_pane->filterGeneralTags->fill('IAmTheSame');
                }
            );

            $region->addPane($listing_pane, $callable);

            // Save the modal and make sure it succeeds.
            $this->landingPagePanelsPage->checkArrival();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Assert that the correct node title is shown in the administrative view.
            $this->assertTextPresent($data[$lang_code]['title']);
        }
    }
}
