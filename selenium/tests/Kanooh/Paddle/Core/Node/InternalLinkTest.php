<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Node\InternalLinkTest.
 */

namespace Kanooh\Paddle\Core\Node;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InternalLinkTest extends WebDriverTestCase
{

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

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

        // Instantiate some objects for later use.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->layoutPage = new LayoutPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests that nodes are correctly shown in autocompletes.
     *
     * @group responsibleAuthorTestBase
     */
    public function testInternalAutocomplete()
    {
        // Create the node.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Publish the node.
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Edit the page for a new concept version.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());

        // Create a new node.
        $nid_with_link = $this->contentCreationService->createBasicPage();

        // Add a link to the other page.
        $this->nodeEditPage->go($nid_with_link);
        $this->nodeEditPage->body->buttonLink->click();

        $modal = $this->nodeEditPage->body->modalLink;
        $modal->waitUntilOpened();
        $modal->linkInfoForm->link->fill($title);

        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);

        // Use the arrow keys to select the result, and press enter to confirm.
        $this->keys(Keys::DOWN . Keys::ENTER);

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $this->layoutPage->go($nid_with_link);

        // Add the pane to a region.
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);

        $top_text = $this->alphanumericTestDataProvider->getValidValue();
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $top_text, $title) {
                $content_type->topSection->enable->check();
                $content_type->topSection->text->fill($top_text);
                $content_type->topSection->urlTypeRadios->internal->select();
                $content_type->getForm()->autocompleteField->fill($title);

                $autocomplete = new AutoComplete($webdriver);
                $autocomplete->waitUntilDisplayed();
                $autocomplete->waitUntilSuggestionCountEquals(1);
                $autocomplete->pickSuggestionByPosition(0);
            }
        );
        $region->addPane($content_type, $callable);

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
