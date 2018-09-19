<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ListingPaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\Paddle\Pages\Element\Pane\ListingPane;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends WebDriverTestCase
{

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->layoutPage = new LayoutPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->editPage = new EditPage($this);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests filter on a term name that exists in multiple vocabularies.
     *
     * To ensure it's not just the last created term that gets used.
     */
    public function testFilterOnTermNameThatExistsMultipleTimes()
    {
        // Create a term with the same term name in both Padde vocabularies.
        $term_name = $this->alphanumericTestDataProvider->getValidValue();
        $taxonomy_service = new TaxonomyService();
        $term_ids[] = $taxonomy_service->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_name);
        $term_ids[] = $taxonomy_service->createTerm(TaxonomyService::TAGS_VOCABULARY_ID, $term_name);

        // Create 1 page for each term.
        $node_ids = array();
        foreach ($term_ids as $key => $term_id) {
            $node_id = $this->contentCreationService->createBasicPage();
            $node_ids[] = $node_id;

            // Link the term.
            $this->editPage->go($node_id);
            if ($key ==  0) {
                $this->editPage->generalVocabularyTermReferenceTree->getTermById($term_id)->select();
            } else {
                $this->editPage->tags->value($term_name);
                $this->editPage->tagsAddButton->click();
                $this->waitUntilTextIsPresent(ucfirst($term_name));
            }
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Publish the page.
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a basic page with a listing pane filtering on the first created term.
        $basic_page_node_id = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_node_id);
        $region = $this->layoutPage->display->getRandomRegion();
        $listing_pane_config = new ListingPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($listing_pane_config, $term_name) {
                $listing_pane_config->filterGeneralTags->fill($term_name);
                $listing_pane_config->filterTags->clear();
            }
        );
        $pane = $region->addPane($listing_pane_config, $callable);
        $listing_pane = new ListingPane($this, $pane->getUuid(), $pane->getXPathSelector());

        // Ensure the tagged page appears in the listing.
        $this->assertTrue($listing_pane->nodeExistsInListing($node_ids[0]));
        $this->assertFalse($listing_pane->nodeExistsInListing($node_ids[1]));

        // Edit the pane and filter on the other term.
        $listing_pane->toolbar->buttonEdit->click();
        $listing_pane->editPaneModal->waitUntilOpened();
        $listing_pane_config = new ListingPanelsContentType($this);
        $listing_pane_config->filterGeneralTags->clear();
        $listing_pane_config->filterTags->fill($term_name);
        $listing_pane->editPaneModal->submit();
        $listing_pane->editPaneModal->waitUntilClosed();
        // Get the refreshed listing pane.
        $listing_pane = new ListingPane($this, $pane->getUuid(), $pane->getXPathSelector());

        // Ensure only that page appears in the listing.
        $this->assertFalse($listing_pane->nodeExistsInListing($node_ids[0]));
        $this->assertTrue($listing_pane->nodeExistsInListing($node_ids[1]));
    }
}
