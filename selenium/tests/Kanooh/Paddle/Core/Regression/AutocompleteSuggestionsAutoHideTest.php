<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\AutocompleteSuggestionsAutoHideTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test that the autocomplete suggestion drop-down is hidden after a
 * suggestion is selected.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-3578
 */
class AutocompleteSuggestionsAutoHideTest extends WebDriverTestCase
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
     * Test that the autocomplete suggestion drop-down is hidden after a
     * suggestion is selected.
     *
     * @group regression
     */
    public function testAutocompleteCorrectlyHides()
    {
        $creation_service = new ContentCreationService($this, $this->userSessionService);
        // Create a page which we can find in the autocomplete.
        $creation_service->createBasicPage();

        $landing_page = $creation_service->createLandingPage();
        $this->layoutPage->go($landing_page);

        // Open the "Add page content" pane configuration form.
        $region = $this->layoutPage->display->getRandomRegion();

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();

        $content_pane = new NodeContentPanelsContentType($this);
        $modal->selectContentType($content_pane);

        // Make sure the autocomplete suggestions drop-down closes.
        $content_pane->getForm()->nodeContentAutocomplete->fill('node');
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
        $autocomplete->waitUntilNoLongerDisplayed();

        // Close the page so the log-out can happen.
        $modal->close();
        $modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();

        $admin_node_view = new ViewPage($this);
        $admin_node_view->checkArrival();
    }
}
