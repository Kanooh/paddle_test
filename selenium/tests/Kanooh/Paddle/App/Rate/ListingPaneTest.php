<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\Rate;

use Kanooh\Paddle\Apps\Rate;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as editNodePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Pages\Element\Pane\ListingPane;
use Kanooh\Paddle\Utilities\CleanUpService;

/**
 * Tests the listing pane with the News view modes.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends WebDriverTestCase
{

    /**
     * Instance of the ContentCreationService used to create content.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The layout page.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The node edit page.
     *
     * @var EditNodePage
     */
    protected $editNodePage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editNodePage = new EditNodePage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->cleanUpService = new CleanUpService($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Rate);

        // Go to the configure page and enable the Basic Page type.
        $this->configurePage->go();
        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Remove previously existent Basic pages.
        $this->cleanUpService->deleteEntities('node', 'basic_page');
    }

    /**
     * Tests if the sorting of nodes per Rating is correct.
     *
     * @group Rate
     * @group listingPane
     */
    public function testSortingByRate()
    {
        // Create 3 basic pages, the first having no votes, the second 4 votes
        // and the last one having 2 votes.
        $title_1 = "A";
        $title_2 = "B";
        $title_3 = "C";
        $this->createPageAndVote($title_1, 0);
        $this->createPageAndVote($title_2, 4);
        $this->createPageAndVote($title_3, 2);

        // Create a landing page.
        $landing_page = $this->contentCreationService->createLandingPage();

        // Go to the the Landing Page's layout page.
        $this->panelsContentPage->go($landing_page);

        // Add a new listing pane.
        $region = $this->panelsContentPage->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $listing_pane = new ListingPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($listing_pane);

        // Check the Basic page
        $listing_pane->basicPageCheckBox->check();

        // Sort by creation date, ascending.
        $listing_pane->sortingRateAsc->select();

        // Save the configuration.
        $modal->submit();
        $modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        // Save the page.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check if the rating values are correctly sorted.
        $frontend_pane = new ListingPane($this, $pane_uuid);
        $listed_nodes_asc = $frontend_pane->getListedNodes();
        $this->areNodesSortedByRate($listed_nodes_asc, "asc");

        // Now sort by rating but descending.
        $this->panelsContentPage->go($landing_page);
        $pane_new->toolbar->buttonEdit->click();
        $pane_new->editPaneModal->waitUntilOpened();
        $listing_pane->sortingRateDesc->select();
        $pane_new->editPaneModal->submit();
        $pane_new->editPaneModal->waitUntilClosed();

        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $listed_nodes_desc = $frontend_pane->getListedNodes();
        $this->areNodesSortedByRate($listed_nodes_desc, "desc");
    }

    /**
     * Creates a Basic page and votes for it.
     * @param string $title
     *  The title of the Basic page.
     * @param int $vote
     *  The amount of stars you vote for.
     */
    protected function createPageAndVote($title, $vote)
    {
        $nid = $this->contentCreationService->createBasicPage($title);

        // Enable Rating for the page.
        $this->contentCreationService->enableRating($nid);

        // Publish the page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Vote.
        if ($vote > 0 && $vote <= 5) {
            $this->frontEndViewPage->go($nid);
            $element = $this->byCssSelector(".star-" . $vote . " a");
            $element->click();
            sleep(1);
        }
    }

    /**
     * Checks if the nodes are sorted correctly.
     *
     * @param $sorted_array
     * @param $sort_order
     */
    protected function areNodesSortedByRate($sorted_array, $sort_order)
    {
        if ($sort_order == "asc") {
            $asc_array = array("A", "C", "B");
            $diff = array_diff($sorted_array, $asc_array);
        } elseif ($sort_order == "desc") {
            $desc_array = array("B", "C", "A");
            $diff = array_diff($sorted_array, $desc_array);
        }
        $this->assertTrue(empty($diff));
    }
}
