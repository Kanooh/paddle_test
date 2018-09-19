<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\PaneTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Pane\Poll\PollPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewPage as FrontEndPollPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Product pane.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
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
     * @var PollPage
     */
    protected $editPage;

    /**
     * Landing page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Front end node view page.
     *
     * @var FrontEndPollPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new PollPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->viewPage = new FrontEndPollPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Poll);
    }

    /**
     * Tests the basic configuration and functionality of the Product pane.
     *
     * @group panes
     * @group poll
     */
    public function testPane()
    {
        // Delete all polls so the autocomplete suggests only ours.
        $service = new CleanUpService($this);
        $service->deleteEntities('node', 'poll');

        // Create a poll page.
        $poll_nid = $this->contentCreationService->createPollPage();
        $this->editPage->go($poll_nid);

        // Add a question.
        $question = $this->alphanumericTestDataProvider->getValidValue(20) . '?';
        $this->editPage->pollForm->question->fill($question);

        // Get the two choices generated for us.
        $choices = array(
            $this->editPage->pollForm->choiceTable->rows[0]->text->getContent(),
            $this->editPage->pollForm->choiceTable->rows[1]->text->getContent(),
        );

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a node to use for the pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Add the Product to a pane in the node.
        $pane_uuid = $this->addPollToPane($nid, $poll_nid);

        // Check the front-end functioning of the form.
        $this->viewPage->go($nid);
        // Get the pane element from the page.
        $poll_pane = new PollPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');

        // Make sure the results are not displayed.
        $this->assertEmpty($poll_pane->pollView->results);

        // Make sure the question is there and the choices are displayed.
        $this->assertEquals($question, $poll_pane->pollView->pollQuestion);
        $this->assertEquals($choices[0], $poll_pane->pollView->votingForm->pollChoices[0]->getLabel());
        $this->assertEquals($choices[1], $poll_pane->pollView->votingForm->pollChoices[1]->getLabel());

        // Submit a vote.
        $poll_pane->pollView->votingForm->pollChoices[1]->select();
        $this->moveto($poll_pane->pollView->votingForm->votingButton);
        $poll_pane->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        // Now we should have the results displayed.
        try {
            $poll_pane->pollView->votingForm;
            $this->fail();
        } catch (\Exception $e) {
            // Do nothing - all is fine.
        }
        $expected_results = array(
            array('choice' => $choices[0], 'percent' => 0),
            array('choice' => $choices[1], 'percent' => 100),
        );

        foreach ($expected_results as $index => $result) {
            $this->assertEquals($result['choice'], $poll_pane->pollView->results[$index]['choice_text']);
            $this->assertEquals($result['percent'], $poll_pane->pollView->results[$index]['percent']);
        }
    }

    /**
     * Add a Product pane to a node.
     *
     * @param string $nid
     *   The node id of the node to which to add the Product pane.
     * @param string $poll_nid
     *   The nid of the Product to add.
     *
     * @return string
     *   The pane uuid.
     */
    public function addPollToPane($nid, $poll_nid)
    {
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new PollPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($content_type, $poll_nid, $webdriver) {
                if ($poll_nid) {
                    $content_type->getForm()->autocompleteField->fill('node/' . $poll_nid);
                    $autocomplete = new AutoComplete($webdriver);
                    $autocomplete->waitUntilDisplayed();
                    $autocomplete->waitUntilSuggestionCountEquals(1);
                    $autocomplete->pickSuggestionByPosition(0);
                }
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $pane_uuid;
    }
}
