<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the responsible author field.
 */
abstract class ResponsibleAuthorTestBase extends WebDriverTestCase
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
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The random data generator.
     *
     * @var Random
     */
    protected $random;

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
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Provides invalid user name input.
     *
     * @see testFieldWithInvalidInput()
     */
    public function invalidInputProvider()
    {
        return array(
            array('nonexistinguser'),
            array('demo'),
        );
    }


    /**
     * Test behavior on invalid input.
     *
     * @param $invalidInput
     *   Invalid input for the responsible author field.
     *
     * @dataProvider invalidInputProvider()
     *
     * @group contentType
     * @group editing
     * @group responsibleAuthorTestBase
     */
    public function testFieldWithInvalidInput($invalidInput)
    {
        // Log in as editor.
        $this->userSessionService->login('Editor');

        // Create the node.
        $nid = $this->setupNode();

        // Provide the invalid input for the responsible author field.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->responsibleAuthor->fill($invalidInput);

        // Check that the validation message appears.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->nodeEditPage->checkArrival();
        $this->assertTextPresent("Unable to find user {$invalidInput}");

        // Cancel the page to prevent subsequent tests to be bothered by an
        // alert window.
        $this->nodeEditPage->contextualToolbar->buttonBack->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Provides names of users for logging in, before testing the autocomplete.
     *
     * The suggestions from the autocomplete should be equal for the
     * user accounts that have different roles. Thus the test needs to be
     * repeated for a user account of each role.
     *
     * @see testAutocomplete()
     */
    public function autocompleteLoginProvider()
    {
        return array(
            array('Editor'),
            array('ChiefEditor'),
            array('SiteManager'),
        );
    }

    /**
     * Test that a dropdown with suggestions shows up when typing in a part of
     * the user name.
     *
     * @param string $login_user
     *   User name to log in with.
     *
     * @dataProvider autocompleteLoginProvider()
     *
     * @group contentType
     * @group editing
     * @group responsibleAuthorTestBase
     */
    public function testAutocomplete($login_user)
    {
        // Log in as the user under test.
        $this->userSessionService->login($login_user);

        // Create the node of the content type under test.
        $nid = $this->setupNode();

        // Edit the node and invoke the autocomplete results.
        $this->nodeEditPage->go($nid);
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
        $autocomplete->pickSuggestionByPosition(0);

        // Verify the right value was filled in. The uid of the demo user can be
        // any digit(s), so we should use a regular expression.
        $regex = '/demo \(\d+\)/';
        $matches = array();
        preg_match_all($regex, $this->nodeEditPage->responsibleAuthor->getContent(), $matches);
        $this->assertCount(1, $matches[0]);

        // Cancel the page to prevent subsequent tests to be bothered by an
        // alert window.
        $this->nodeEditPage->contextualToolbar->buttonBack->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Test that the responsible author is shown in the meta data.
     *
     * @group contentType
     * @group editing
     * @group nodeMetadataSummary
     * @group responsibleAuthorTestBase
     */
    public function testResponsibleIsStoredAndShownInMetadata()
    {
        // Log in as editor.
        $this->userSessionService->login('Editor');

        // Create a node of the content type under test.
        $nid = $this->setupNode();

        // Edit the node and fill in a valid responsible author.
        $this->nodeEditPage->go($nid);

        $author_name = 'demo';
        $this->nodeEditPage->responsibleAuthor->fill($author_name);

        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(4);
        $autocomplete->pickSuggestionByPosition(0);

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->nodeSummary->showAllMetadata();

        // Check that the responsible author is shown in the node metadata
        // summary.
        $responsible_author_indicator = $this->adminNodeViewPage->nodeSummary->getMetadata('created', 'page-responsible-author');

        $this->assertEquals($author_name, $responsible_author_indicator['value']);
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
