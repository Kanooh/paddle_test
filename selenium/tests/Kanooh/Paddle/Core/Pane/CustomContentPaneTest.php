<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\CustomContentPaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage as FrontEndLandingPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for the pane sections of different panes.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CustomContentPaneTest extends WebDriverTestCase
{

    /**
     * The administrative node view of a landing page.
     *
     * @var LandingPageViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The front-end view of a landing page.
     *
     * @var LandingPageViewPage
     */
    protected $frontendLandingPage;

    /**
     * The Panels IPE display of a landing page.
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

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->frontendLandingPage = new FrontEndLandingPage($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Login to the application first.
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
     * Tests that the div for the pane body is empty when the body itself is
     * empty.
     *
     * @group panes
     */
    public function testEmptyPaneBodyNotRendered()
    {
        // Create a random landingpage page.
        $service = new ContentCreationService($this, $this->userSessionService);
        $nid = $service->createLandingPage();

        $this->administrativeNodeViewPage->checkArrival();

        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Select a region to put a pane in.
        $region = $this->layoutPage->display->getRandomRegion();
        $region_id = $region->id();
        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($custom_content_pane);

        // Submit and wait until closed to ensure there are no validation errors.
        $modal->submit();
        $modal->waitUntilClosed();

        // We need the UUID for the front-end check.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane_new->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();

        // Get the front-end pane.
        $frontend_pane = new CustomContentPane($this, $pane_uuid);

        // Check that the body is not shown.
        $this->assertFalse($frontend_pane->checkBodyDisplayedInPane());

        // Fill out the body and verify the pane is shown in the frontend.
        $this->layoutPage->go($nid);
        $pane_new->toolbar->buttonEdit->click();
        $pane_new->editPaneModal->waitUntilOpened();
        $custom_content_pane->fillInConfigurationForm();
        $pane_new->editPaneModal->submit();
        $pane_new->editPaneModal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();

        // Get the front-end pane.
        $frontend_pane = new CustomContentPane($this, $pane_uuid);

        // Check that the body is shown.
        $this->assertTrue($frontend_pane->checkBodyDisplayedInPane());
    }
}
