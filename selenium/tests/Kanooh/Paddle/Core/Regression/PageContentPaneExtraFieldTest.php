<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\PageContentPaneExtraFieldTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the fieldset title of the additional fields is present only when
 * there are field.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-3573
 */
class PageContentPaneExtraFieldTest extends WebDriverTestCase
{

    /**
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->layoutPage = new PanelsContentPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Test that there is no extra drop-down in the "Add page content" pane
     * add/edit form.
     *
     * @group regression
     */
    public function testExtraDropDownNotDisplayed()
    {
        $creation_service = new ContentCreationService($this, $this->userSessionService);
        $nid = $creation_service->createLandingPage();
        $this->layoutPage->go($nid);

        // Open the "Add page content" pane configuration form.
        $region = $this->layoutPage->display->getRandomRegion();

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();

        $content_pane = new NodeContentPanelsContentType($this);
        $modal->selectContentType($content_pane);

        // Assert the field is not there.
        $this->assertFalse($content_pane->titleOverride);

        // Close the page so the log-out can happen.
        $modal->close();
        $modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();

        $admin_node_view = new ViewPage($this);
        $admin_node_view->checkArrival();
    }
}
