<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\PollTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewPage as FrontEndPollPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Product Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PollTest extends WebDriverTestCase
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
     * @var FrontEndPollPage
     */
    protected $frontendPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new PollPage($this);
        $this->frontendPage = new FrontEndPollPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Poll);
    }

    /**
     * Tests the poll nodes editing.
     *
     * @group editing
     * @group poll
     */
    public function testPollEditing()
    {
        $nid = $this->contentCreationService->createPollPage();
        $this->editPage->go($nid);

        // Prepare some values.
        $question_text = $this->alphanumericTestDataProvider->getValidValue();
        $choices_texts = array();
        for ($i = 0; $i < 3; $i++) {
            $choices_texts[] = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Add a question text.
        $this->editPage->pollForm->question->fill($question_text);

        // Fill the two choices available by default and then add a third one.
        $rows = $this->editPage->pollForm->choiceTable->rows;

        $rows[0]->text->fill($choices_texts[0]);
        $rows[1]->text->fill($choices_texts[1]);

        $this->editPage->pollForm->addChoice();

        $rows = $this->editPage->pollForm->choiceTable->rows;
        $rows[2]->text->fill($choices_texts[2]);

        // Close the poll for voting. By default it is open.
        $this->editPage->pollForm->pollStatusRadioButtons->closed->select();

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Edit again to check the values saved.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        $this->assertEquals($question_text, $this->editPage->pollForm->question->getContent());

        $rows = $this->editPage->pollForm->choiceTable->rows;
        foreach ($choices_texts as $index => $choices_text) {
            $this->assertEquals($choices_text, $rows[$index]->text->getContent());
        }

        $this->assertTrue($this->editPage->pollForm->pollStatusRadioButtons->closed->isSelected());

        $this->editPage->contextualToolbar->buttonSave->click();
    }

    /**
     * Test how the polls are rendered on the front-end.
     */
    public function testPollRendering()
    {
        // Create a poll to work with.
        $nid = $this->contentCreationService->createPollPage();
        $this->editPage->go($nid);

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
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        // Make sure the question is there and the choices are displayed and
        // that the results are not displayed.
        $this->assertPollFormRendering($this->frontendPage, $question, $choices);

        // Choose the first one and submit the vote.
        $this->frontendPage->pollView->votingForm->pollChoices[0]->select();
        $this->moveto($this->frontendPage->pollView->votingForm->votingButton);
        $this->frontendPage->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        // Now we should have the results displayed.
        try {
            $this->frontendPage->pollView->votingForm;
            $this->fail('The voting form should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // The form is not shown, which is the expected behaviour.
        }
        $expected_results = array(
            array('choice' => $choices[0], 'percent' => 100),
            array('choice' => $choices[1], 'percent' => 0),
        );
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);

        // Now switch the user so we can vote again.
        $this->userSessionService->switchUser('Editor');
        $this->frontendPage->go($nid);

        $this->frontendPage->pollView->votingForm->pollChoices[1]->select();
        $this->frontendPage->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        // Now assert the results displayed.
        $expected_results[0]['percent'] = 50;
        $expected_results[1]['percent'] = 50;
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);

        // Now close the poll for voting.
        $this->editPage->go($nid);
        $this->editPage->pollForm->pollStatusRadioButtons->closed->select();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendPage->go($nid);

        // Check that the results are displayed directly.
        try {
            $this->frontendPage->pollView->votingForm;
            $this->fail('The voting form should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // The form is not shown, which is the expected behaviour.
        }
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);
    }

    /**
     * Tests the closed functionality.
     */
    public function testPollClosed()
    {
        // Create a poll to work with.
        $nid = $this->contentCreationService->createPollPage();
        $this->editPage->go($nid);

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
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go on the front=end and vote. Choose the first option and submit.
        $this->frontendPage->go($nid);
        $this->frontendPage->pollView->votingForm->pollChoices[0]->select();
        $this->moveto($this->frontendPage->pollView->votingForm->votingButton);
        $this->frontendPage->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        $expected_results = array(
          array('choice' => $choices[0], 'percent' => 100),
          array('choice' => $choices[1], 'percent' => 0),
        );
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);

        $this->editPage->go($nid);
        $this->editPage->pollForm->pollStatusRadioButtons->closed->select();

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendPage->go($nid);
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);

        // Log out, clear the cookies and check that you only see the results
        // after closing the poll.
        $this->userSessionService->logout();
        $this->userSessionService->clearCookies();
        $this->frontendPage->go($nid);
        $this->assertPollResultsRendering($this->frontendPage, $expected_results);
    }

    /**
     * Check the functioning of the Product rendered in a pane on the front-end.
     *
     * @param mixed $element
     *   The element (pane or page) to which the poll has been added.
     * @param string $question
     *   The question in the poll.
     * @param array $choices
     *   An array with the choice texts.
     */
    public function assertPollFormRendering($element, $question, $choices)
    {
        // Make sure the results are not displayed.
        $this->assertEmpty($element->pollView->results);

        // Make sure the question is there and the choices are displayed.
        $this->assertEquals($question, $element->pollView->pollQuestion);
        $this->assertEquals($choices[0], $element->pollView->votingForm->pollChoices[0]->getLabel());
        $this->assertEquals($choices[1], $element->pollView->votingForm->pollChoices[1]->getLabel());
    }

    /**
     * Asserts that the poll results are what we expect.
     *
     * @param mixed $element
     *   The element (pane or page) to which the poll has been added.
     * @param array $results
     *   The expected results. Each element of the array should be an array containing 2 elements:
     *     - 'choice': the text of the choice
     *     - 'percent': the expected percent of voters who voted for this choice
     */
    protected function assertPollResultsRendering($element, $results)
    {
        foreach ($results as $index => $result) {
            $this->assertEquals($result['choice'], $element->pollView->results[$index]['choice_text']);
            $this->assertEquals($result['percent'], $element->pollView->results[$index]['percent']);
        }
    }
}
