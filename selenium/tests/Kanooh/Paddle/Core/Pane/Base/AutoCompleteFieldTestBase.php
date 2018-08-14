<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\AutoCompleteFieldTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalNodeApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class AutoCompleteFieldTestBase extends WebDriverTestCase
{

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
     * @var AutoComplete
     */
    protected $autoComplete;

    /**
     * @var DrupalNodeApi
     */
    protected $drupalNodeApi;

    /**
     * @var array
     *  Keyed by node id.
     */
    protected $nodeData;

    /**
     * @var AddPaneModal
     */
    protected $addPaneModal;

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
     * Gets the auto complete text input form field we're about to test.
     *
     * @return \Kanooh\Paddle\Pages\Element\Form\AutoCompletedText
     */
    abstract public function getAutoCompleteFormField();

    /**
     * Get the panels content type that contains the form we might need.
     *
     * @return \Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType
     */
    abstract public function getPanelsContentType();

    /**
     * Get the node its field names we can search upon.
     *
     * @return string[]
     */
    abstract public function getSearchableNodeFields();

    /**
     * Get the auto complete suggestion we should get.
     *
     * @param int $node_id
     *   Node id.
     * @return string
     *   The string representation of this node as a suggestion.
     */
    abstract public function getExpectedSuggestion($node_id);

    /**
     * Get parts of the auto complete suggestion we should get.
     *
     * Can be used for testing as search string.
     *
     * @param int $node_id
     *   Node id.
     * @return string[]
     *   An array of partial suggestions.
     */
    abstract public function getPartialExpectedSuggestions($node_id);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->landingPagePanelsPage = new LandingPagePanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->autoComplete = new AutoComplete($this);
        $this->drupalNodeApi = new DrupalNodeApi($this, $this->base_url);
        $this->nodeData = array();
        $this->addPaneModal = new AddPaneModal($this);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Test auto complete suggestion.
     *
     * @group autoCompleteFieldTestBase
     */
    public function testSuggestions()
    {
        // Create 2 nodes, so testing if only 1 suggestion appears is relevant.
        $nids = array($this->setupNode(), $this->setupNode());
        $this->goToTestPage();

        $searchable_node_fields = $this->getSearchableNodeFields();
        $form_field = $this->getAutoCompleteFormField();
        $expected_suggestions = array(
            $this->getExpectedSuggestion($nids[0]),
            $this->getExpectedSuggestion($nids[1])
        );

        foreach ($nids as $key => $nid) {
            foreach ($searchable_node_fields as $searchable_node_field) {
                $form_field->fill($this->getNodeFieldValue($nid, $searchable_node_field));
                $this->assertExactlyOneSuggestion($expected_suggestions[$key]);
                // Clearing the field twice fixes issues on consecutive usages
                // of the autocomplete field.
                // @see KANWEBS-3071
                $form_field->clear();
            }
            // Test if searching for a part of the suggestion yields the same result.
            foreach ($this->getPartialExpectedSuggestions($nid) as $partial_suggestion) {
                $form_field->fill($partial_suggestion);
                $this->assertExactlyOneSuggestion($expected_suggestions[$key]);
                // Clearing the field twice fixes issues on consecutive usages
                // of the autocomplete field.
                // @see KANWEBS-3071
                $form_field->clear();
            }
        }

        $this->leaveTestPage();
    }

    /**
     * Test unsafe input values.
     *
     * Do some checks on the auto complete for search strings ending on '/'.
     * To check for XSS vulnerabilities insert some JavaScript.
     *
     * @group autoCompleteFieldTestBase
     */
    public function testUnsafeInput()
    {
        // Create a node that shouldn't appear, because it doesn't have the
        // prefix added to the title.
        $this->setupNode();

        $test_data_provider = new AlphanumericTestDataProvider();
        $prefix = $test_data_provider->getValidValue();

        // Create nodes with unsafe values in their title.
        $titles = array(
            // Contains a forward slash.
            "$prefix/You can see him",
            // Contains script tags with a Javascript alert.
            "$prefix like pizza<script>alert('Oops');</script>"
        );
        // And collect their expected suggestions.
        $expected_suggestions = array();
        foreach ($titles as $title) {
            $nid = $this->setupNode($title);
            $expected_suggestions[] = $this->getExpectedSuggestion($nid);
        }

        $this->goToTestPage();
        $form_field = $this->getAutoCompleteFormField();
        $form_field->fill($prefix);

        // We expect to see our 2 nodes with a prefix.
        $this->autoComplete->waitUntilSuggestionCountEquals(2);
        foreach ($this->autoComplete->getSuggestions() as $suggestion) {
            $this->assertTrue(in_array(trim($suggestion), $expected_suggestions));
        }
        // Now add '/' at the end - this should find only the first node.
        $form_field->fill($prefix . '/');
        $this->autoComplete->waitUntilSuggestionCountEquals(1);
        $suggestion = $this->autoComplete->getSuggestions();
        $this->assertEquals($expected_suggestions[0], $suggestion[0]);

        $this->leaveTestPage();
    }

    /**
     * Asserts there's only 1 suggestion and it should match the given one.
     *
     * @param string $expected_suggestion
     *   The auto complete suggestion to expect.
     */
    public function assertExactlyOneSuggestion($expected_suggestion)
    {
        $this->autoComplete->waitUntilSuggestionCountEquals(1);
        foreach ($this->autoComplete->getSuggestions() as $suggestion) {
            $this->assertEquals($expected_suggestion, trim($suggestion));
        }
    }

    /**
     * Go to the right page (and modal) to test on.
     */
    public function goToTestPage()
    {
        // Create a landing page.
        $this->contentCreationService->createLandingPage();

        // Go to 'Page layout' page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Open the Add Pane dialog.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        // Select the pane type in the modal dialog.
        $this->addPaneModal->selectContentType($this->getPanelsContentType());
    }

    /**
     * Get out of the test page.
     *
     * So the subsequent tests are not buggered by an alert.
     */
    public function leaveTestPage()
    {
        $this->addPaneModal->close();
        $this->addPaneModal->waitUntilClosed();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Get the value of a certain field of a certain node.
     *
     * @param int $node_id
     *   Node id.
     * @param string $node_field
     *   Node field name.
     *
     * @return string
     *   Node field value.
     */
    public function getNodeFieldValue($node_id, $node_field)
    {
        if ($node_field == 'nid') {
            return 'node/' . $this->nodeData[$node_id][$node_field];
        } else {
            return $this->nodeData[$node_id][$node_field];
        }
    }
}
