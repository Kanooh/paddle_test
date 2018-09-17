<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeDetailTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the node detail pages.
 */
abstract class NodeDetailTestBase extends WebDriverTestCase
{

    /**
     * The administrative node view.
     *
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The frontend node view page.
     *
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

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

        // Instantiate some objects for later use.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as editor.
        $this->userSessionService->login('Editor');
    }

    /**
     * Test behavior when the body is left empty.
     *
     * @group contentType
     * @group editing
     * @group nodeDetailTestBase
     */
    public function testEmptyBody()
    {
        // Create the node.
        $nid = $this->setupNode();

        // The body is empty, so no html for it should be shown in the front
        // end.
        // Provide the invalid input for the responsible author field.
        $this->frontEndNodeViewPage->go($nid);
        $this->assertFalse($this->frontEndNodeViewPage->body);

        // Set a body.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue(16));
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Check in the front end that the body is shown.
        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertTrue($this->frontEndNodeViewPage->body->displayed());
    }

    /**
     * Test that fields are not shown on the page.
     *
     * Covers:
     *  - responsible author
     *  - tags
     *  - SEO title
     *  - SEO description
     *
     * @group contentType
     * @group editing
     * @group nodeDetailTestBase
     */
    public function testFieldsNotShown()
    {
        $nid = $this->setupNode();
        $this->nodeEditPage->go($nid);

        $seo_title = $this->alphanumericTestDataProvider->getValidValue();
        $seo_description = $this->alphanumericTestDataProvider->getValidValue();

        $this->nodeEditPage->seoTitleField->value($seo_title);
        $this->nodeEditPage->seoDescriptionField->value($seo_description);
        $this->nodeEditPage->responsibleAuthor->fill('dem');

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(4);

        // Check that the suggestions match the expected results.
        $suggestions = $autocomplete->getSuggestions();
        $expected_suggestions = array(
            'demo',
            'demo_chief_editor',
            'demo_editor',
            'demo_read_only',
        );
        $this->assertEquals($expected_suggestions, $suggestions);

        // Choose the 'demo' suggestion.
        $autocomplete->pickSuggestionByPosition(0, true);

        // Add a tag.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $this->nodeEditPage->tags->clear();
        $this->nodeEditPage->tags->value($tag);
        $this->nodeEditPage->tagsAddButton->click();
        $this->waitUntilTextIsPresent(ucfirst($tag));

        // Save and check that the fields are not shown on the admin node view
        // and front end view of the node.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->assertFalse($this->adminNodeViewPage->checkClassPresent('field-name-field-page-responsible-author'));
        $this->assertFalse($this->adminNodeViewPage->checkClassPresent('field-name-field-paddle-tags'));
        $this->assertFalse($this->adminNodeViewPage->checkClassPresent('field-name-field-paddle-seo-title'));
        $this->assertFalse($this->adminNodeViewPage->checkClassPresent('field-name-field-paddle-seo-description'));

        $this->adminNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('field-name-field-page-responsible-author'));
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('field-name-field-paddle-tags'));
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('field-name-field-paddle-seo-title'));
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('field-name-field-paddle-seo-description'));
    }

    /**
     * Tests that some node properties are not shown in frontend view.
     *
     * Covers:
     * - node author picture;
     * - node submitted data.
     */
    public function testPropertiesNotShown()
    {
        // Create the node.
        $nid = $this->setupNode();

        // Add some text in the body. This is needed to display the node
        // template.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $this->frontEndNodeViewPage->go($nid);
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('user-picture'));
        $this->assertFalse($this->frontEndNodeViewPage->checkClassPresent('submitted'));
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
