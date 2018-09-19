<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\TagValidationRegressionTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests whether tags do not disappear when form validation errors occur.
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-1675
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TagValidationRegressionTest extends WebDriverTestCase
{

    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->nodeEditPage = new NodeEditPage($this);

        // Log in as an editor.
        $this->userSessionService->login('Editor');
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
     * Data provider for testTagValidationRegression().
     *
     * Returns a list of callbacks to create nodes of certain types.
     *
     * @return array
     *   An array containing arrays of node creation callbacks.
     */
    public function nodeSetupProvider()
    {
        return array(
           array('setupBasicPage'),
           array('setupLandingPage'),
           array('setupSimpleContactPage'),
        );
    }

    /**
     * Creates a new basic page.
     */
    public function setupBasicPage()
    {
        return $this->contentCreationService->createBasicPage();
    }

    /**
     * Creates a new landing page.
     */
    public function setupLandingPage()
    {
        return $this->contentCreationService->createLandingPage();
    }

    /**
     * Creates a new simple contact page.
     */
    public function setupSimpleContactPage()
    {
        return $this->contentCreationService->createSimpleContact();
    }

    /**
     * Tests whether tags do not disappear when form validation errors occur.
     *
     * @dataProvider nodeSetupProvider
     *
     * @group editing
     * @group regression
     * @group taxonomy
     */
    public function testTagValidationRegression($callback)
    {
        // Create a node.
        $nid = $this->$callback();

        // Edit the node.
        $this->nodeEditPage->go($nid);

        // Cause a validation error by removing the node title which is a
        // required field.
        $this->nodeEditPage->title->clear();

        // Open the tag fieldset if it is closed.
        if (in_array('folded', explode(' ', $this->nodeEditPage->taxonomyContainer->attribute('class')))) {
            $this->nodeEditPage->taxonomyContainerTitle->click();
            $this->nodeEditPage->waitUntilFieldsetIsOpen($this->nodeEditPage->taxonomyContainer);
        }

        // Add a tag.
        $tag = trim(ucfirst($this->alphanumericTestDataProvider->getValidValue(16)));
        $this->nodeEditPage->tags->clear();
        $this->nodeEditPage->tags->value($tag);
        $this->nodeEditPage->tagsAddButton->click();
        $this->waitUntilElementIsPresent('//span[contains(@class, "at-term-text") and text() = "' . $tag . '"]');

        // Save the page. Check that validation failed.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Title field is required.');
        $this->nodeEditPage->checkArrival();

        // Open the tag fieldset again if it is closed.
        if (in_array('folded', explode(' ', $this->nodeEditPage->taxonomyContainer->attribute('class')))) {
            $this->nodeEditPage->taxonomyContainerTitle->click();
            $this->nodeEditPage->waitUntilFieldsetIsOpen($this->nodeEditPage->taxonomyContainer);
        }

        // Check that the tag is still there.
        $this->waitUntilElementIsPresent('//span[contains(@class, "at-term-text") and text() = "' . $tag . '"]');

        // Check that a new tag can still be added.
        $tag = trim(ucfirst($this->alphanumericTestDataProvider->getValidValue(16)));
        $this->nodeEditPage->tags->clear();
        $this->nodeEditPage->tags->value($tag);
        $this->nodeEditPage->tagsAddButton->click();
        $this->waitUntilElementIsPresent('//span[contains(@class, "at-term-text") and text() = "' . $tag . '"]');

        // Set a non-empty title to allow saving without errors.
        $this->nodeEditPage->title->value($this->alphanumericTestDataProvider->getValidValue());
        // Save the page to prevent alerts from screwing up the next test.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
