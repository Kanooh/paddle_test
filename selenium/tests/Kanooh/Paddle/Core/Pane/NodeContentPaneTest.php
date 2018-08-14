<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\NodeContentPaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeContentPaneTest extends WebDriverTestCase
{
    /**
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var LandingPageViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->viewPage = new LandingPageViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the basic usage of a content pane.
     */
    public function testNodeContentPane()
    {
        // Create a basic page.
        $title = $this->alphanumericTestDataProvider->getValidValue(255);
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $basic_page_nid = $this->contentCreationService->createBasicPage($title);
        $this->editPage->go($basic_page_nid);
        $this->editPage->body->setBodyText($body);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Create a landing page.
        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);

        // Add a pane with the content of the basic page to the landing page.
        $content_type = new NodeContentPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $basic_page_nid, $title) {
                $content_type->getForm()->nodeContentAutocomplete->fill($title . ' (node/' . $basic_page_nid . ')');
            }
        );
        $region = $this->layoutPage->display->getRandomRegion();

        $pane = $region->addPane($content_type, $callable);

        // Ensure the correct pane content gets shown.
        $pane_uuid = $pane->getUuid();
        $this->assertNotEmpty($pane_uuid);
        $this->assertTextPresent($body);
    }

    /**
     * Tests the page title option of content panes.
     */
    public function testPageTitleOption()
    {
        // Create a basic page.
        $title = $this->alphanumericTestDataProvider->getValidValue(255);
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $basic_page_nid = $this->contentCreationService->createBasicPage($title);
        $this->editPage->go($basic_page_nid);
        $this->editPage->body->setBodyText($body);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Create a landing page.
        $landing_page_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($landing_page_nid);

        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new NodeContentPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);
        $content_type->getForm()->nodeContentAutocomplete->fill('node/' . $basic_page_nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();

        // Select the page title option.
        $content_type->getForm()->viewModeRadios->{'2'}->select();
        $content_type = new NodeContentPanelsContentType($this);
        sleep(2);

        $top_enabled = $content_type->topSection->enable->isChecked();
        $title_chosen = $content_type->topSection->contentTypeRadios->title->isSelected();
        $link_picked = $content_type->topSection->urlTypeRadios->nodeLink->isSelected();

        $this->assertTrue($top_enabled);
        $this->assertTrue($title_chosen);
        $this->assertTrue($link_picked);

        $modal->submit();
        $modal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->viewPage->go($landing_page_nid);

        // Assert that the title is shown on the front-end.
        $this->assertTextPresent($title);
        // Assert that the body is hidden on the front-end.
        $this->assertTextNotPresent($body);
    }
}
