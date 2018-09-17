<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\QuizTest.
 */

namespace Kanooh\Paddle\App\Quiz;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\AddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\EditPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\QuizViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\QuizService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider as Random;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class QuizTest
 * @package Kanooh\Paddle\App\Quiz
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class QuizTest extends WebDriverTestCase
{
    /**
     * Administrative node view.
     *
     * @var AdminViewPage
     */
    protected $adminNodeView;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * Configuration page for the quiz paddlet.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Page to edit an existing quiz.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * Front-end node view.
     *
     * @var QuizViewPage
     */
    protected $nodeView;

    /**
     * @var Random
     */
    protected $random;

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

        // Prepare some classes for the tests.
        $this->adminNodeView = new AdminViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->nodeView = new QuizViewPage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the Quiz app.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Quiz());

        // Bootstrap Drupal.
        $drupal = new DrupalService();
        if (!$drupal->isBootstrapped()) {
            $drupal->bootstrap($this);
        }

        // Login as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests if the previous/next button functionality works on quiz w/o info.
     */
    public function testParticipationStepsWithoutInfo()
    {
        $data = QuizService::generateRandomData($this);
        // Create a new quiz and quiz page that are both published.
        $qid = $this->createPublishedQuiz($data, false);
        $nid = $this->createPublishedQuizPage($this->random->getValidValue(6), $qid);

        // Go to the quiz page.
        $this->nodeView->go(array($nid));
        $this->nodeView->assertLayoutMarkup();
        $form = $this->nodeView->quizForm;

        // Go to the next screen, which should be the first question.
        $form->startScreen->nextButton->click();
        $form->waitUntilScreenIsVisible('question');
        $form = $this->nodeView->quizForm;

        // Save the first question for use later.
        $first_question = $form->questionScreen->question;
        $title = $form->questionScreen->title;
        // Answer the question and go to the next question.
        $form->questionScreen->answers[2]->select();
        $form->questionScreen->nextButton->click();
        $this->waitUntilTextIsPresent($title);
        $form = $this->nodeView->quizForm;
        // Answer the question.
        $form->questionScreen->answers[1]->select();

        // Wait until the title of the previous question is visible.
        $form->questionScreen->previousButton->click();
        $this->waitUntilTextIsPresent($first_question);
        $form = $this->nodeView->quizForm;

        // Assert that the filled in value is the same.
        $this->assertTrue($form->questionScreen->answers[2]->isSelected());
    }

    /**
     * Tests the participating in a quiz.
     */
    public function testParticipation()
    {
        $data = QuizService::generateRandomData($this);

        // Increase the length of 1 question and 1 answer to 255 characters and
        // include an unsafe HTML character to ensure this doesn't prevent
        // participations from being saved.
        $data['questions'][0]['title'] = $this->random->getValidValue(399) . '"';
        $data['questions'][0]['answers'][0] = $this->random->getValidValue(399) . '"';

        // Create a new quiz and quiz page that are both published.
        $qid = $this->createPublishedQuiz($data, true);
        $nid = $this->createPublishedQuizPage($this->random->getValidValue(6), $qid);

        // Go to the quiz page.
        $this->nodeView->go(array($nid));
        $this->nodeView->assertLayoutMarkup();

        // Make sure the full screen span is available for screen readers.
        $this->byCssSelector('.paddle-quiz-full-screen-open span.visuallyhidden');
        $this->assertTextPresent(t('Full screen'));

        $form = $this->nodeView->quizForm;

        // Make sure the start screen displays the correct image, title, ...
        $expected_title = !empty($data['customize']['startTitle']) ? $data['customize']['startTitle'] : $data['title'];
        $this->assertEquals($expected_title, $form->startScreen->title);
        $this->assertEquals($data['customize']['startSubtitle'], $form->startScreen->subtitle);
        $this->assertEquals($data['customize']['startMessage'], $form->startScreen->message);
        $this->assertEquals($data['customize']['startButtonLabel'], $form->startScreen->nextButton->value());

        $atom = scald_atom_load($data['design']['startImage']['id']);
        $filename = pathinfo($atom->file_source, PATHINFO_FILENAME);
        $this->assertContains($filename, $form->startScreen->image->attribute('src'));

        // Go to the next screen, which should be the user info screen.
        $form->startScreen->nextButton->click();
        $form->waitUntilScreenIsVisible('info');
        $form = $this->nodeView->quizForm;

        // Make sure the info screen displays the correct title, message, ...
        $this->assertEquals($data['customize']['infoTitle'], $form->infoScreen->title);
        $this->assertEquals($data['customize']['infoMessage'], $form->infoScreen->message);
        $this->assertEquals($data['customize']['infoButtonLabel'], $form->infoScreen->nextButton->value());

        // Make sure the "name" field is not present.
        $this->assertFalse($form->infoScreen->isNameFieldPresent());

        // Click the button to the next step and make sure we get a validation
        // error.
        $form->infoScreen->nextButton->click();
        $this->waitUntilTextIsPresent('Your e-mail address field is required.');
        $form = $this->nodeView->quizForm;

        // Enter an invalid e-mail address and make sure we get another
        // validation error.
        $email_provider = new EmailTestDataProvider();
        $form->infoScreen->email->fill($email_provider->getInvalidValue());
        $form->infoScreen->nextButton->click();
        $this->waitUntilTextIsPresent('The e-mail address you entered is not valid.');
        $form = $this->nodeView->quizForm;

        // Enter a valid e-mail address and continue. Store the e-mail address
        // in a variable so we can re-use it later and check that our results
        // will be overwritten when we re-take the quiz.
        $email = $email_provider->getValidValue();
        $form->infoScreen->email->fill($email);
        $inexact_start_time = time();
        $form->infoScreen->nextButton->click();
        $form->waitUntilScreenIsVisible('question');
        $form = $this->nodeView->quizForm;

        // Go back to the info screen, make sure the e-mail address is still
        // filled in.
        $form->questionScreen->previousButton->click();
        $form->waitUntilScreenIsVisible('info');
        $form = $this->nodeView->quizForm;
        $this->assertEquals($email, $form->infoScreen->email->getContent());

        // Refresh the page and make sure we're still on the info screen and the
        // e-mail address is still filled in.
        $this->nodeView->reloadPage();
        $form = $this->nodeView->quizForm;
        $this->assertEquals($email, $form->infoScreen->email->getContent());

        // Go to the first question.
        $form->infoScreen->nextButton->click();
        $form->waitUntilScreenIsVisible('question');
        $form = $this->nodeView->quizForm;

        // Go over all questions and answer each one.
        $answered = array();
        foreach ($data['questions'] as $index => $question_data) {
            // If we're past the first question, go back to the previous
            // question.
            if ($index > 0) {
                $previous_index = $index - 1;
                $previous_question_data = $data['questions'][$previous_index];

                // Wait until the title of the previous question is visible.
                $form->questionScreen->previousButton->click();
                $this->waitUntilTextIsPresent($previous_question_data['title']);
                $form = $this->nodeView->quizForm;

                // Make sure the previously selected answer is still selected.
                $selected = $answered[$previous_index];
                $this->assertTrue($form->questionScreen->answers[$selected]->isSelected());

                // Refresh the page and make sure we're still on the same
                // question and the answer is still selected.
                $this->nodeView->reloadPage();
                $form = $this->nodeView->quizForm;
                $this->assertTextPresent($previous_question_data['title']);
                $this->assertTrue($form->questionScreen->answers[$selected]->isSelected());

                // Go to the next question again.
                $form->questionScreen->nextButton->click();
                $this->waitUntilTextIsPresent($question_data['title']);
                $form = $this->nodeView->quizForm;
            }

            // Title of the question screen should be the quiz title.
            $this->assertEquals($data['title'], $form->questionScreen->title);

            // Subtitle should indicate the progress of the questions.
            $question_no = $index + 1;
            $max_no = count($data['questions']);
            $subtitle = 'Question ' . $question_no . ' of ' . $max_no;
            $this->assertEquals($subtitle, $form->questionScreen->subtitle);

            // Verify that the question is displayed correctly.
            $this->assertEquals($question_data['title'], $form->questionScreen->question);

            // Verify that all answers are displayed.
            $answers = $form->questionScreen->answers;
            foreach ($question_data['answers'] as $answer_index => $answer_label) {
                $answer = $answers[$answer_index];
                $this->assertEquals($answer_label, $answer->getLabel());
            }

            // Go to the next question without answering, and make sure we get
            // a validation error.
            $form->questionScreen->nextButton->click();
            $this->waitUntilTextIsPresent('Please select an answer before continuing.');
            $form = $this->nodeView->quizForm;

            // Indicate the correct answer for the odd questions (index = even),
            // incorrect one for the even questions (index = odd).
            if ($index % 2 == 0) {
                $answered[$index] = $question_data['correct_answer'];
            } else {
                $answered[$index] = ($question_data['correct_answer'] == 0) ? 1 : 0;
            }
            $form->questionScreen->answers[$answered[$index]]->select();

            // Go to the next question, or the tiebreaker question.
            $form->questionScreen->nextButton->click();
            if ($index + 1 < count($data['questions'])) {
                $next_question_data = $data['questions'][$index + 1];
                $this->waitUntilTextIsPresent($next_question_data['title']);
            } else {
                $form->waitUntilScreenIsVisible('tiebreaker');
            }
            $form = $this->nodeView->quizForm;
        }

        // Make sure the tiebreaker screen shows the correct title, subtitle,...
        $this->assertEquals($data['title'], $form->tiebreakerScreen->title);
        $this->assertEquals('Tiebreaker', $form->tiebreakerScreen->subtitle);
        $this->assertEquals($data['customize']['tiebreakerQuestion'], $form->tiebreakerScreen->question);
        $this->assertEquals($data['customize']['tiebreakerLabel'], $form->tiebreakerScreen->answerSuffix);

        // Click the button to go to the next step, and make sure we get a
        // validation error.
        $form->tiebreakerScreen->nextButton->click();
        $this->waitUntilTextIsPresent('Answer field is required');
        $form = $this->nodeView->quizForm;

        // Enter an answer and continue to the result screen.
        $tiebreaker_answer = $this->random->getValidValue(8);
        $form->tiebreakerScreen->answer->fill($tiebreaker_answer);
        $form->tiebreakerScreen->nextButton->click();
        $form->waitUntilScreenIsVisible('result');
        $inexact_end_time = time();
        $form = $this->nodeView->quizForm;

        // Make sure the result screen shows the correct title, subtitle, ...
        $this->assertEquals($data['title'], $form->resultScreen->title);
        $this->assertEquals($data['customize']['resultTitle'], $form->resultScreen->subtitle);
        $this->assertEquals($data['customize']['resultMessage'], $form->resultScreen->message);

        // The score should be 50% if the questions are even, or half + 1 if
        // uneven.
        $total = count($data['questions']);
        $score = floor($total / 2);
        if ($total % 2 == 1) {
            $score++;
        }
        $score_percentage = floor(($score / $total) * 100);
        $this->assertEquals($score_percentage . '%', $form->resultScreen->score);

        // Get the participation records from the database.
        $participations = QuizService::getParticipations($qid, $email);

        // Make sure there's only one participation record for the quiz and
        // given e-mail address.
        $this->assertCount(1, $participations);
        $participation = $participations[0];

        // Verify that the scores have been saved correctly.
        $this->assertParticipationScore($score, $total, $score_percentage, $participation);

        // Verify that the answers and tiebreaker have been saved correctly.
        $this->assertParticipationAnswers($data['questions'], $answered, $tiebreaker_answer, $participation);

        // Verify that the start and end time have acceptable values.
        $this->assertParticipationTimestamps($inexact_start_time, $inexact_end_time, $participation);

        // Go to the quiz's edit page and go to the customize step.
        $this->editPage->go(array($qid));
        $this->editPage->qaForm->customizeStepButton->click();
        $this->editPage->checkArrival();

        // Disable the tiebreaker question.
        $this->editPage->customizeForm->tiebreaker->uncheck();

        // Set the location of the info screen to the end.
        $this->editPage->customizeForm->infoLocationEnd->select();

        // The info screen should also ask the name of the user.
        $this->editPage->customizeForm->infoNameAndEmail->select();

        // Save the quiz.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Get the amount of participation records.
        $participation_count = count(QuizService::getParticipations($qid));

        // Participate in the quiz again, twice. Once using a different e-mail
        // address, and once again using the previous e-mail address.
        $alt_emails = $email_provider->getValidDataSet();
        $email_index = array_search($email, $alt_emails);
        $alt_email_index = $email_index == 0 ? 1 : 0;
        $alt_email = $alt_emails[$alt_email_index];

        $answers = array();
        foreach ($data['questions'] as $question_data) {
            $answers[] = $question_data['correct_answer'];
        }

        foreach (array($alt_email, $email) as $index => $current_email) {
            $name = $this->random->getValidValue(8);

            $node_view = new QuizViewPage($this);
            $node_view->go(array($nid));

            // Set the quiz in full screen mode so we can check that the whole
            // quiz still works even in in full screen mode.
            $node_view->quizForm->startScreen->fullScreenOpenLink->click();

            // "Participate" in the quiz.
            $inexact_start_time = time();
            QuizService::participateViaUI($this, $node_view, $data, array(
                'email' => $current_email,
                'name' => $name,
                'answers' => $answers,
            ));
            $inexact_end_time = time();

            // Close full screen mode before verifying the score.
            $node_view->quizForm->resultScreen->fullScreenCloseLink->click();

            // Check the score.
            $this->assertEquals('100%', $this->nodeView->quizForm->resultScreen->score);

            // Get the participation record from the database.
            $participations = QuizService::getParticipations($qid, $current_email);

            // Make sure there's always only one participation record for a
            // given e-mail address, even if we use the one that was used
            // before.
            $this->assertCount(1, $participations);
            $participation = $participations[0];

            // Verify that the user's name has been saved correctly.
            $this->assertEquals($name, $participation->name);

            // Verify that the score and tiebreaker have been saved correctly.
            $total = count($data['questions']);
            $this->assertParticipationScore($total, $total, 100, $participation);

            // Verify that the tiebreaker and other answers have been saved
            // correctly.
            $this->assertParticipationAnswers($data['questions'], $answers, '', $participation);

            // Verify that the start and end time have acceptable values.
            $this->assertParticipationTimestamps($inexact_start_time, $inexact_end_time, $participation);
        }

        // Make sure there's exactly one new participation record, not two.
        // (Because we overwrote one by using the same e-mail address as before)
        $this->assertCount($participation_count + 1, QuizService::getParticipations($qid));
    }

    /**
     * Tests the disclaimer and its open/close links.
     */
    public function testDisclaimer()
    {
        // Create a new quiz and quiz page that are both published.
        $data = QuizService::generateRandomData($this);
        $disclaimer = $data['customize']['disclaimer'];
        $qid = $this->createPublishedQuiz($data, true);
        $nid = $this->createPublishedQuizPage($this->random->getValidValue(6), $qid);

        // Go to the quiz page.
        $this->nodeView->go(array($nid));
        $form = $this->nodeView->quizForm;

        // Click the disclaimer link. (Should be visible as we generated a
        // random disclaimer.)
        $this->assertTrue($form->startScreen->isDisclaimerLinkPresent());
        $form->startScreen->disclaimerLink->click();

        // Wait until the disclaimer is visible.
        $form->startScreen->waitUntilDisclaimerIsVisible();
        $this->assertTextPresent($disclaimer);

        // Close the disclaimer by clicking the 'x' link.
        $form->startScreen->disclaimerCloseLink->click();
        $form->startScreen->waitUntilDisclaimerIsHidden();

        // Open the disclaimer again.
        $form->startScreen->disclaimerLink->click();
        $form->startScreen->waitUntilDisclaimerIsVisible();

        // Close it again using the close button.
        $form->startScreen->disclaimerCloseButton->click();
        $form->startScreen->waitUntilDisclaimerIsHidden();

        // Edit the quiz, set the disclaimer to a not-empty string that should
        // be interpreted as empty. Basically we set the CKEditor content to two
        // <p> tags containing a non-breaking whitespace each and a line break
        // in between. This is the same as pressing enter twice in the CKEditor.
        // Note that we need to add an additional backslash before the newline
        // character because we insert the content using javascript and the
        // backslash in the newline character would escape the string if we
        // don't put another one in front of it. This would result in a
        // "unterminated string literal" exception.
        $empty_disclaimer = '<p>&nbsp;</p>\\' . PHP_EOL . '<p>&nbsp;</p>';
        $this->editPage->go(array($qid));
        $this->editPage->qaForm->customizeStepButton->click();
        $this->editPage->checkArrival();
        $this->editPage->customizeForm->disclaimer->setBodyText($empty_disclaimer);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Go back to the quiz on the front-end and make sure the disclaimer is
        // not visible.
        $this->nodeView->go(array($nid));
        $this->assertFalse($this->nodeView->quizForm->startScreen->isDisclaimerLinkPresent());
    }

    /**
     * Tests the customizable design of a quiz.
     *
     * @group css
     */
    public function testCustomDesign()
    {
        // Create a new quiz and quiz page that are both published.
        $data = QuizService::generateRandomData($this);
        $qid = $this->createPublishedQuiz($data, true);
        $nid = $this->createPublishedQuizPage($this->random->getValidValue(6), $qid);

        // Go to the quiz's edit design page.
        $this->editPage->go(array($qid, 'design'));

        // Upload a custom CSS file.
        $file = $this->file(dirname(__FILE__) . '/../../assets/quiz.css');
        $this->editPage->designForm->cssFile->chooseFile($file);

        // Save the quiz.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Go to the quiz page on the front-end.
        $this->nodeView->go(array($nid));
        $form = $this->nodeView->quizForm;

        // Make sure the start button's css "color" property is set to red. This indicates that the custom CSS has been
        // loaded. Note that Selenium always returns colors as rgba() values, even if set as hexadecimal or
        // human-readable words in the actual css file.
        $red = 'rgba(255, 0, 0, 1)';
        $this->assertEquals($red, $form->startScreen->nextButton->css('color'));
    }

    /**
     * Tests that the personal info can be now optional.
     */
    public function testNoInfoRequired()
    {
        $add_page = new AddPage($this);

        // Go to the configuration page and get all Quiz IDs.
        $this->configurePage->go();
        $qids = $this->configurePage->quizTable->getQids();

        // Go to the quiz add page.
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $add_page->checkArrival();

        // Fill in the title, questions and answers.
        $add_page->qaForm->title->fill($this->random->getValidValue());
        $add_page->qaForm->getQuestion(0)->title->fill($this->random->getValidValue());
        $add_page->qaForm->getQuestion(0)
            ->getAnswer(0)
            ->fill($this->random->getValidValue());
        $add_page->qaForm->getQuestion(0)->addAnswer();
        $add_page->qaForm->getQuestion(0)
            ->getAnswer(1)
            ->fill($this->random->getValidValue());

        // Click the button to go to the next step. (Customization)
        $add_page->qaForm->nextStepButton->click();
        $add_page->checkArrival();

        // Verify you can unselect the info required field and that none of the
        // info fields are shown.
        $add_page->customizeForm->infoRequired->uncheck();
        $this->assertFalse($add_page->customizeForm->infoLocationEnd->isDisplayed());
        $this->assertFalse($add_page->customizeForm->infoLocationStart->isDisplayed());
        $this->assertFalse($add_page->customizeForm->infoTitle->isDisplayed());
        $this->assertFalse($add_page->customizeForm->infoMessage->getWebdriverElement()->displayed());
        $this->assertFalse($add_page->customizeForm->infoNameAndEmail->isDisplayed());
        $this->assertFalse($add_page->customizeForm->infoButtonLabel->isDisplayed());

        // Save the quiz.
        $add_page->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Make sure there's exactly one new quiz. (Important because a
        // potential bug could be that a new quiz is created between each
        // step.)
        $new_qids = $this->configurePage->quizTable->getQids();
        $this->assertCount(count($qids) + 1, $new_qids);

        // Return the new quiz's id.
        $new_qids = array_values(array_diff($new_qids, $qids));
        $qid = $new_qids[0];

        // Publish the quiz.
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $row->status->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');
        $status = $this->configurePage->quizTable->getRowByQid($qid)->status;
        $this->assertTrue($status->isChecked());

        // Create a quiz page and make sure you can fill out the quiz without
        // needing to enter your personal info.
        $nid = $this->contentCreationService->createQuizPageViaUI(null, $qid);

        // Participate twice and check if the participation is saved twice.
        for ($i = 1; $i < 3; $i++) {
            // Do the goto to make sure we go around varnish.
            //todo this should be done in a better manner ie: purge varnish...
            drupal_goto('node/' . $nid . '?' . $this->random->getValidValue());
            $this->nodeView->quizForm->startScreen->nextButton->click();

            // Make sure we land on the question screen instead of the info screen.
            $this->nodeView->quizForm->waitUntilScreenIsVisible('question');
            $form = $this->nodeView->quizForm;
            $form->questionScreen->answers[1]->select();
            $form->questionScreen->nextButton->click();
            $form->waitUntilScreenIsVisible('result');

            // Get the participation record from the database.
            $participations = QuizService::getParticipations($qid);
            $this->assertEquals($i, count($participations));
        }
    }

    /**
     * Tests that the style selector on the image fields works.
     */
    public function testImageFieldsCropStyles()
    {
        // Generate some data for the test.
        $data = array(
            'title' => $this->random->getValidValue(8),
            'questions' => array(
                array(
                    'title' => $this->random->getValidValue(8),
                    'image' => $this->assetCreationService->createImage(),
                    'answers' => array(
                        $this->random->getValidValue(8),
                    ),
                    'correct_answer' => 0,
                ),
            ),
            'design' => array(
                'startImage' => $this->assetCreationService->createImage(array('image_style' => '4_3')),
            ),
        );

        // Create a new quiz.
        $qid = $this->createPublishedQuiz($data, false);

        // Go to the design step.
        $this->editPage->go($qid);
        $this->editPage->customizeForm->designStepButton->click();
        $this->editPage->checkArrival();
        $this->editPage->designForm->startImage->waitUntilDisplayed();

        // Select a style for the start image.
        $drupalAjaxApi = new DrupalAjaxApi($this);
        $this->editPage->designForm->startImage->style->selectOptionByValue('4_3');
        $drupalAjaxApi->waitUntilElementFinishedAjaxing($this->editPage->designForm->startImage->style->getWebdriverElement());
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Edit again the quiz to see that the values are kept.
        $this->editPage->go($qid);
        $this->editPage->customizeForm->designStepButton->click();
        $this->editPage->checkArrival();
        $this->assertEquals(
            $data['design']['startImage']['id'],
            $this->editPage->designForm->startImage->valueField->value()
        );
        $this->assertEquals(
            $data['design']['startImage']['image_style'],
            $this->editPage->designForm->startImage->style->getSelectedValue()
        );
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create a quiz page for this quiz.
        $nid = $this->createPublishedQuizPage(null, $qid);

        // Verify that the start image is shown using the selected style.
        $this->nodeView->go($nid);

        // Generate the path to the style image.
        $scald_atom = scald_atom_load($data['design']['startImage']['id']);
        $expected_src = file_create_url(image_style_path(
            $data['design']['startImage']['image_style'],
            $scald_atom->file_source
        ));

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $this->nodeView->quizForm->startScreen->image->attribute('src'));

        // Go to the first question of the quiz.
        $form = $this->nodeView->quizForm;
        $form->startScreen->nextButton->click();
        $form->waitUntilScreenIsNoLongerPresent('start');

        // Verify that the image here is showing the normal path.
        $scald_atom = scald_atom_load($data['questions'][0]['image']['id']);
        $expected_src = file_create_url($scald_atom->file_source);
        $this->assertStringStartsWith(
            $expected_src,
            $this->nodeView->quizForm->questionScreen->image->attribute('src')
        );
    }

    /**
     * Creates a quiz with random data and publishes it.
     *
     * @param array $fields
     *   Field data as generated by QuizService::generateRandomData().
     * @param $customized
     *   Whether the Quiz Customized step is filled in.
     *
     * @return int
     *  Quiz id of the created quiz entity.
     */
    protected function createPublishedQuiz($fields, $customized)
    {
        // Create a new quiz via the UI.
        $qid = QuizService::createViaUI($this, $fields, false, false, $customized);

        // Publish the quiz via the UI.
        $this->configurePage->go();
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $row->status->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');
        $status = $this->configurePage->quizTable->getRowByQid($qid)->status;
        $this->assertTrue($status->isChecked());

        return $qid;
    }

    /**
     * Creates a quiz page and publishes it.
     *
     * @param string $title
     *   Title for the page.
     * @param int $qid
     *   Quiz id to reference to.
     *
     * @return int
     *   Node id of the created quiz page.
     */
    protected function createPublishedQuizPage($title = null, $qid = null)
    {
        // Create a new quiz page via the UI.
        $nid = $this->contentCreationService->createQuizPageViaUI($title, $qid);

        // Publish the quiz page.
        $this->adminNodeView->go(array($nid));
        $this->adminNodeView->contextualToolbar->buttonPublish->click();
        $this->adminNodeView->checkArrival();

        return $nid;
    }

    /**
     * Checks that the scores stored in a participation record are correct.
     *
     * @param int $score
     *   Amount of correct answers.
     * @param int $score_max
     *   Maximum possible correct answers.
     * @param int $score_percentage
     *   Number between 0 and 100.
     * @param object $participation
     *  Participation record as returned by QuizService::getParticipations().
     */
    protected function assertParticipationScore($score, $score_max, $score_percentage, $participation)
    {
        $this->assertEquals($score, $participation->score);
        $this->assertEquals($score_max, $participation->score_max);
        $this->assertEquals($score_percentage, $participation->score_percentage);
    }

    /**
     * Checks that the answers stored in a participation record are correct.
     *
     * @param array $questions
     *   Questions array as generated by QuizService::generateRandomData().
     * @param int[] $answers
     *   Provided answers to the questions.
     * @param string $tiebreaker
     *   Tiebreaker answer. Can be an empty string.
     * @param object $participation
     *  Participation record as returned by QuizService::getParticipations().
     */
    protected function assertParticipationAnswers($questions, $answers, $tiebreaker, $participation)
    {
        $this->assertEquals($tiebreaker, $participation->tiebreaker);
        foreach ($questions as $index => $question) {
            $participation_answer = $participation->answers[$index];
            $this->assertEquals($question['title'], $participation_answer->question);

            $answer = $answers[$index];
            $answer_label = $question['answers'][$answer];
            $this->assertEquals($answer_label, $participation_answer->answer);

            $correct = ($answer == $question['correct_answer']) ? 1 : 0;
            $this->assertEquals($correct, $participation_answer->correct);
        }
    }

    /**
     * Checks that the timestamps stored in a participation record are correct.
     *
     * @param int $start
     *   Timestamp that or after the participation should have ended.
     * @param int $end
     *   Timestamp that or before the participation should have ended.
     * @param object $participation
     *  Participation record as returned by QuizService::getParticipations().
     */
    protected function assertParticipationTimestamps($start, $end, $participation)
    {
        // Allow a margin of error on the start and end times, as there may be a slight difference between time when
        // running the tests on a different machine as the test site server. The most common example of this is running
        // the website in a vagrant box and running the Selenium tests on the host machine. To fix this we lower the
        // inexact start time by a second, and add a second to the inexact end time.
        $start -= 1;
        $end += 1;

        $this->assertNotEmpty($participation->start);
        $this->assertNotEmpty($participation->end);
        $this->assertTrue($participation->start < $participation->end);
        $this->assertTrue($participation->end <= $end);
        $this->assertTrue($participation->start >= $start);
    }
}
