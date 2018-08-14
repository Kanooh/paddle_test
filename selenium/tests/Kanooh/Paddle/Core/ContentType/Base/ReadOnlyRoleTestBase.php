<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ReadOnlyRoleTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentDiscoverySearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPane;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the typical workflow of a user with the read only role.
 */
abstract class ReadOnlyRoleTestBase extends WebDriverTestCase
{
    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The administrative node view.
     *
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The 'Search' content discovery page.
     *
     * @var ContentDiscoverySearchPage
     */
    protected $contentDiscoverySearchPage;

    /**
     * The dashboard.
     *
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The front end node view page.
     *
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * The front page.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The preview toolbar.
     *
     * @var PreviewToolbar
     */
    protected $previewToolbar;

    /**
     * The user session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

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
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->contentDiscoverySearchPage = new ContentDiscoverySearchPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->previewToolbar = new PreviewToolbar($this);

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the typical workflow of a user with the read only role.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1763
     *
     * @group admin
     * @group workflow
     * @group readOnlyRoleTestBase
     */
    public function testReadOnlyRole()
    {
        $published_title = $this->alphanumericTestDataProvider->getValidValue();
        $revision_title = $this->alphanumericTestDataProvider->getValidValue();

        // Create a page for each content type.
        $nid = $this->setupNode($published_title);
        $this->adminNodeViewPage->go($nid);

        // Publish the page.
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Create a new revision of the page.
        $this->adminNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $this->nodeEditPage->title->clear();
        $this->nodeEditPage->title->value($revision_title);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Log in as read only user.
        $this->userSessionService->switchUser('ReadOnly');

        // Verify that you are redirected to the dashboard after logging in.
        $this->dashboardPage->checkArrival();

        // Verify that only the "Dashboard" and "Content" links are available in
        // the administration menu.
        $this->dashboardPage->adminMenuLinks->checkLinks(array('Dashboard', 'Content'));
        $this->dashboardPage->adminMenuLinks->checkLinksNotPresent(array('Structure', 'PaddleStore', 'Themes', 'Users'));

        // Verify that the "Mine in review", "All in review",
        // "Planned unpublications" and "All recent unpublished" dashboard
        // status panes are not shown.
        // @todo Which panes are / are not visible is not yet formally decided
        //   and can still change in the future.
        $pane_presence = array(
            DashboardPane::ALL_IN_REVIEW => false,
            DashboardPane::ALL_PUBLISHED => true,
            DashboardPane::ALL_TO_CHECK => true,
            DashboardPane::ALL_UNPUBLISHED => false,
            DashboardPane::MINE_IN_REVIEW => false,
            DashboardPane::PLANNED_PUBLICATIONS => true,
            DashboardPane::PLANNED_UNPUBLICATIONS => false,
        );

        foreach ($pane_presence as $xpath_selector => $is_present) {
            $pane = new DashboardPane($this, $xpath_selector);
            $this->assertEquals($is_present, $pane->isPresent());
        }

        // Verify that the node shows up in the 'All Published' dashboard pane.
        $pane = new DashboardPane($this, DashboardPane::ALL_PUBLISHED);
        $row = $pane->getRowByTitle($published_title);

        // Click on the "view" icon and verify it leads to the front end view.
        $row->links->linkView->click();
        $this->frontEndNodeViewPage->checkArrival();
        $arguments = $this->frontEndNodeViewPage->getPathArguments();
        $this->assertEquals($nid, $arguments[0]);

        // Click on the close button in the front end view and verify this leads
        // to the administrative node view.
        $this->frontEndNodeViewPage->previewToolbar->closeButton->click();
        $this->adminNodeViewPage->checkArrival();

        // Go back to the dashboard. Click on the "edit" icon and verify it
        // leads to the administrative node view.
        $this->dashboardPage->go();
        $pane = new DashboardPane($this, DashboardPane::ALL_PUBLISHED);
        $row = $pane->getRowByTitle($published_title);
        $row->links->linkAdminView->click();
        $this->adminNodeViewPage->checkArrival();

        // Go back to the dashboard. Click on the "more" links and verify they
        // go to the content discovery search.
        $this->dashboardPage->go();
        $pane = new DashboardPane($this, DashboardPane::ALL_PUBLISHED);
        $pane->moreLink->click();
        $this->contentDiscoverySearchPage->checkArrival();

        // Click on "Content" in the main menu. This should also lead to the
        // content discovery search.
        $this->contentDiscoverySearchPage->adminContentLinks->linkContent->click();
        $this->contentDiscoverySearchPage->checkArrival();

        // On the content discovery tabs, verify that the node is listed.
        $row = $this->contentDiscoverySearchPage->contentTable->getNodeRowByNid($nid);

        // Verify that the delete icon is not visible.
        $row->links->checkLinksNotPresent(array('Delete'));

        // Verify that the view and edit icons are visible.
        $row->links->checkLinks(array('AdminView', 'FrontView'));

        // Verify that the view icon leads to the front page node view.
        $row->links->linkFrontView->click();
        $this->frontEndNodeViewPage->checkArrival();

        // Go back to the content discovery tabs and check that the admin view icon
        // leads to the administrative node view.
        $this->contentDiscoverySearchPage->go();
        $row = $this->contentDiscoverySearchPage->contentTable->getNodeRowByNid($nid);
        $row->links->linkAdminView->click();
        $this->adminNodeViewPage->checkArrival();

        // On the administrative node view, verify that only the buttons
        // "Preview revision" and "Online version" are visible in the contextual
        // toolbar.
        $this->adminNodeViewPage->contextualToolbar->checkButtons(array('OnlineVersion', 'PreviewRevision'));

        $buttons = array_keys($this->adminNodeViewPage->contextualToolbar->buttonInfo());
        unset($buttons[array_search('OnlineVersion', $buttons)]);
        unset($buttons[array_search('PreviewRevision', $buttons)]);
        $this->adminNodeViewPage->contextualToolbar->checkButtonsNotPresent($buttons);

        // Click on "Preview revision".
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();

        // Verify that the current draft is visible in the front end.
        $this->waitForText($revision_title);

        // Verify that the toolbar is visible at the top of the page in the
        // frontend.
        $this->assertTrue($this->previewToolbar->isPresent());

        // Click on the "close" button to go to the administration section.
        $this->previewToolbar->closeButton->click();
        $this->adminNodeViewPage->checkArrival();

        // Click on "Online version".
        $this->adminNodeViewPage->contextualToolbar->buttonOnlineVersion->click();

        // Verify that the published revision is visible in the front end.
        $this->waitForText($published_title);
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
