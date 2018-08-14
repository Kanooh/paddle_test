<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ListingPaneTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\Paddle\Pages\Element\Pane\ListingPane;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage as FrontEndLandingPageViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class ListingPaneTestBase extends WebDriverTestCase
{

    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The administrative node view of a landing page.
     *
     * @var LandingPageViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The panels display of a landing page.
     *
     * @var LandingPagePanelsContentPage
     */
    protected $landingPagePanelsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The edit page of a node.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * The front end view of a landing page.
     *
     * @var FrontEndLandingPageViewPage
     */
    protected $frontendLandingPage;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->frontendLandingPage = new FrontEndLandingPageViewPage($this);
        $this->landingPagePanelsPage = new LandingPagePanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->editPage = new EditPage($this);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests adding a listing pane to a page.
     *
     * @group listingPane
     * @group panes
     */
    public function testAdd()
    {
        // Create 2 published nodes.
        $nids = array();
        for ($i = 0; $i < 2; $i++) {
            $nids[$i] = $this->setupNode();
            $this->administrativeNodeViewPage->go($nids[$i]);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Use a landing page to place a listing pane on.
        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->landingPagePanelsPage->go($landing_page_nid);

        // Add a Listing pane to a random region.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $listing_pane = new ListingPanelsContentType($this);
        $pane = $region->addPane($listing_pane);
        $listing_pane = new ListingPane($this, $pane->getUuid(), $pane->getXPathSelector());

        // Check that the correct nodes are displayed.
        foreach ($nids as $nid) {
            $this->assertTrue($listing_pane->nodeExistsInListing($nid));
        }
        $this->assertFalse($listing_pane->nodeExistsInListing($landing_page_nid));

        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The page has been updated.');
        $this->administrativeNodeViewPage->checkArrival();

        // Check that in the front end node view, the correct nodes are displayed.
        foreach ($nids as $nid) {
            $this->assertTrue($listing_pane->nodeExistsInAdminFrontViewListing($nid));
        }
        $this->assertFalse($listing_pane->nodeExistsInAdminFrontViewListing($landing_page_nid));

        // Publish the landing page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Now go to the front end view and verify the correct nodes are shown in the pane.
        $this->frontendLandingPage->go($landing_page_nid);
        foreach ($nids as $nid) {
            $this->assertTrue($listing_pane->nodeExistsInAdminFrontViewListing($nid));
        }
        $this->assertFalse($listing_pane->nodeExistsInAdminFrontViewListing($landing_page_nid));
    }

    /**
     * Tests if the body length in the teaser has been set correctly.
     *
     * @group listingPane
     * @group modals
     * @group panes
     */
    public function testListingTeaserViewMode()
    {
        // Create the node, set the body field and publish it.
        $nid = $this->setupNode();
        $atom = $this->assetCreationService->createImage();

        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $body = $this->alphanumericTestDataProvider->getValidValue(300);
        $this->editPage->body->setBodyText($body);
        $this->editPage->featuredImage->selectAtom($atom['id']);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Create a landing page with a listing pane which uses teasers.
        $this->contentCreationService->createLandingPage();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Add a Listing pane to a random region.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $listing_pane = new ListingPanelsContentType($this);

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->selectContentType($listing_pane);

        // Fill in the configuration form and submit it.
        $listing_pane->viewModeListingTeaserRadioButton->select();
        $modal->submit();
        $modal->waitUntilClosed();

        // We now add an ellipsis during triming, which contains 3 characters.
        // Which means trimmed to 160 there will be 157 characters from the body
        // and 1 ellipsis of three points.
        $trimmed_body = substr($body, 0, 157) . '...';
        $this->assertTextPresent($trimmed_body);
        $this->assertTextNotPresent($body);

        try {
            $this->byCssSelector('.node-listing-teaser .thumbnail img');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            $this->fail('An image should be shown.');
        }

        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The page has been updated.');
        // Check if we're being redirected correctly and publish the page.
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent($trimmed_body);
        $this->assertTextNotPresent($body);

        try {
            $this->byCssSelector('.node-listing-teaser .thumbnail img');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            $this->fail('An image should be shown.');
        }

        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        // Now go to the front end view, and verify the correct nodes are shown in the pane.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->assertTextPresent($trimmed_body);
        $this->assertTextNotPresent($body);

        try {
            $this->byCssSelector('.node-listing-teaser .thumbnail img');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            $this->fail('An image should be shown.');
        }
    }

    /**
     * Tests sorting. Right now this only tests sorting by creation date.
     *
     * @group listingPane
     * @group panes
     */
    public function testSorting()
    {
        $node_type = '';

        // Create some random nodes to list.
        $nodes = array();
        for ($i = 0; $i < 5; $i++) {
            $nid = $this->setupNode();
            $node = node_load($nid);
            $nodes[$nid] = $node->title;

            if (empty($node_type)) {
                $node_type = $node->type;
            }
        }

        // Publish each created node, but in a random order to make sure the
        // creation date sorting isn't actually sorting by publication date.
        // Keep the publication order in case we want to test the sorting by
        // publication date later. Also assign a tag to each page to filter by,
        // so we can filter out pages created before the test.
        $tag = 'listing_tag_' . time();
        $random_nids = array_keys($nodes);
        shuffle($random_nids);
        $publication_order = array();
        foreach ($random_nids as $random_nid) {
            $publication_order[$random_nid] = $nodes[$random_nid];

            $this->administrativeNodeViewPage->go($random_nid);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
            $this->editPage->checkArrival();
            $this->editPage->tags->value($tag);
            $this->editPage->tagsAddButton->click();
            $this->waitUntilTextIsPresent(ucfirst($tag));
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a random landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Select a region to put the listing pane in.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();

        // Select the listing pane type in the modal dialog.
        $listing_pane = new ListingPanelsContentType($this);
        $modal->selectContentType($listing_pane);

        // Set the content type checkbox on the listing pane.
        $checkbox = str_replace('_', ' ', $node_type);
        $checkbox = ucwords($checkbox);
        $checkbox = str_replace(' ', '', $checkbox);
        $checkbox = $checkbox . 'CheckBox';
        $checkbox = lcfirst($checkbox);

        // Filter to only show basic pages with the tag we created in this test.
        $listing_pane->{$checkbox}->check();
        $listing_pane->filterTags->fill($tag);

        // Sort by creation date, ascending.
        $listing_pane->sortingChronologicalCreatedAsc->select();

        // Submit and wait until closed to ensure there are no validation errors.
        $modal->submit();
        $modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();

        // Get the front-end pane and assert that the order of the nodes is
        // correct.
        $frontend_pane = new ListingPane($this, $pane_uuid);
        $this->assertCorrectSort($frontend_pane, $nodes);

        // Now sort by creation date but descending.
        $this->landingPagePanelsPage->go($nid);
        $pane_new->toolbar->buttonEdit->click();
        $pane_new->editPaneModal->waitUntilOpened();
        $listing_pane->sortingChronologicalCreatedDesc->select();
        $pane_new->editPaneModal->submit();
        $pane_new->editPaneModal->waitUntilClosed();

        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();

        // Get the front-end pane and assert that the order of the nodes is
        // correct.
        $frontend_pane = new ListingPane($this, $pane_uuid);
        $this->assertCorrectSort($frontend_pane, array_reverse($nodes, true));
    }

    /**
     * Tests that if the pane has no results we completely avoid output from it.
     *
     * @group listingPane
     * @group panes
     */
    public function testNoResultsOutput()
    {
        // Create a node that will go into the listing and publish it.
        $nid = $this->setupNode();
        $node = node_load($nid);
        $node_type = str_replace(' ', '', ucwords(str_replace('_', ' ', $node->type)));
        $this->contentCreationService->moderateNode($nid, 'published');

        // Create a landing page to hold the listing pane.
        $landing_page_nid = $this->contentCreationService->createLandingPage();

        // Create a tag to filter the nodes with.
        $tag_name = $this->alphanumericTestDataProvider->getValidValue();
        $term = (object) array('vid' => 1, 'name' => $tag_name);
        taxonomy_term_save($term);

        // Add the pane to the basic page.
        $this->landingPagePanelsPage->go($landing_page_nid);
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $content_type = new ListingPanelsContentType($this);

        $callable = new SerializableClosure(
            function () use ($content_type, $tag_name, $node_type) {
                $checkbox = lcfirst($node_type . 'CheckBox');
                $content_type->{$checkbox}->check();
                $content_type->filterTags->fill($tag_name);
            }
        );
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure there is no output from the pane.
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertTextNotPresent('There are no results.');
        $this->assertEmpty($this->elements($this->using('class name')->value('pane-listing')));

        // Add the tag to the node which should be listed.
        $this->editPage->go($nid);
        $this->editPage->tags->value($tag_name);
        $this->editPage->tagsAddButton->click();
        $this->waitUntilTextIsPresent(ucfirst($tag_name));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check that now there is output from the pane.
        $this->frontendLandingPage->go($landing_page_nid);
        $frontend_pane = new ListingPane($this, $pane_uuid, '//div[@data-pane-uuid = "' . $pane_uuid . '"]');
        $this->assertTrue($frontend_pane->nodeExistsInListing($nid));

        // Add a top section to the pane.
        $this->landingPagePanelsPage->go($landing_page_nid);
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type->topSection->enable->check();
        $content_type->topSection->contentTypeRadios->text->select();
        $content_type->topSection->text->fill($this->alphanumericTestDataProvider->getValidValue());
        $pane->editPaneModal->submit();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Remove the tag from the listed node to have no output again.
        $this->editPage->go($nid);
        $this->editPage->deleteTag($term->tid);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Make sure the top section is the only output from the pane.
        $this->frontendLandingPage->go($landing_page_nid);
        $this->assertTextNotPresent('There are no results.');
        $frontend_pane = new ListingPane($this, $pane_uuid, '//div[@data-pane-uuid = "' . $pane_uuid . '"]');
        $this->assertFalse($frontend_pane->nodeExistsInListing($nid));
    }

    /**
     * Asserts that the nodes in a given listing pane are sorted correctly.
     *
     * @param ListingPane $pane
     *   The front-end pane for which to check the sorting.
     * @param array $nodes
     *   Correctly sorted array of node titles, keyed with their nids.
     */
    public function assertCorrectSort(ListingPane $pane, $nodes)
    {
        $pane_nodes = $pane->getListedNodes();
        $diff = array_diff($pane_nodes, $nodes);
        $this->assertTrue(empty($diff));
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
