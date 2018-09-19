<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Quiz;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\AddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\EditPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\QuizPageViewPage;
use Kanooh\Paddle\Pages\Element\Quiz\Delete\ConfirmationModal;
use Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizFormFiller;
use Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizQaForm;
use Kanooh\Paddle\Pages\Element\Quiz\Export\ExportModal;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;
use Kanooh\Paddle\Pages\Node\ViewPage\QuizViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\QuizService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider as Random;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ConfigurationTest
 * @package Kanooh\Paddle\App\Quiz
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Page to add a new quiz.
     *
     * @var AddPage
     */
    protected $addPage;

    /**
     * Admin node view of a quiz page.
     *
     * @var QuizPageViewPage
     */
    protected $adminNodeView;

    /**
     * Quiz app configuration page.
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
        $this->addPage = new AddPage($this);
        $this->adminNodeView = new QuizPageViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->editPage = new EditPage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Login as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the Quiz app.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Quiz);
    }

    /**
     * Tests the configuration of the app.
     */
    public function testOverviewCrudOperations()
    {
        // Delete all quiz entities so we can test the placeholder message on
        // the configuration page.
        QuizService::delete();

        // Go to the configuration page.
        $this->configurePage->go();

        // Verify that there's a placeholder text in the table of quizzes.
        $this->assertTextPresent('You have no quizzes yet. You can create one using the "Create quiz" button above.');
        $this->assertTrue($this->configurePage->quizTable->isEmpty());

        // Click the "create quiz" button in the contextual toolbar.
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $this->addPage->checkArrival();

        // Click the save button without entering a title, and check that we
        // get a validation error.
        $this->addPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Title field is required.');
        $this->waitUntilTextIsPresent('Question 1 field is required.');
        $this->waitUntilTextIsPresent('Answer 1 field is required.');
        $this->waitUntilTextIsPresent('Please indicate a correct answer for question 1.');

        // Click the "cancel" button on the add page and ensure we're redirected
        // back to the configuration page.
        $this->addPage->contextualToolbar->buttonCancel->click();
        $this->configurePage->checkArrival();

        // Generate data for 3 quizzes.
        $quizzes_data = array(
            0 => QuizService::generateRandomData($this),
            1 => QuizService::generateRandomData($this),
            2 => QuizService::generateRandomData($this),
        );

        // Overwrite the generated titles so we know the alphabetic order. The
        // quizzes are not created alphabetically so we know for sure that
        // they're not sorted by created date or quiz id.
        $quizzes_data[0]['title'] = 'Z' . $this->random->getValidValue(8);
        $quizzes_data[1]['title'] = 'A' . $this->random->getValidValue(8);
        $quizzes_data[2]['title'] = 'H' . $this->random->getValidValue(8);

        // Create the three quizzes and ensure that they're saved and sorted
        // correctly in the overview table.
        $quiz_titles = array();
        foreach ($quizzes_data as $quiz_index => $quiz_data) {
            // Store the title of the quiz to sort.
            $title = $quiz_data['title'];
            $quiz_titles[] = $title;

            // Sort the quiz titles alphabetically, and reset the array indices.
            asort($quiz_titles);
            $quiz_titles = array_values($quiz_titles);

            // Create the quiz via the UI.
            QuizService::createViaUI($this, $quiz_data);

            // Assert that the quiz was created.
            $rows = $this->configurePage->quizTable->rows;
            $this->assertCount(count($quiz_titles), $rows);

            // Assert that the quizzes are sorted correctly.
            foreach ($quiz_titles as $index => $quiz_title) {
                $this->assertEquals($quiz_title, $rows[$index]->title);

                // Store the quiz id of the newly created quiz while we're
                // looping over the rows anyway.
                if ($quiz_data['title'] == $quiz_title) {
                    $qid = $rows[$index]->qid;
                    $quizzes_data[$quiz_index]['qid'] = $qid;
                }
            }
        }

        // Generate a new title for the first quiz.
        $old_title = $quizzes_data[0]['title'];
        $new_title = 'C' . $this->random->getValidValue(8);
        $quizzes_data[0]['title'] = $new_title;
        $qid = $quizzes_data[0]['qid'];

        // Go to the edit page for the first quiz.
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $row->editLink->click();

        // Can't use checkArrival() here as it doesn't take path arguments into
        // account when they're not set by go().
        $this->editPage->waitUntilPageIsLoaded();

        // Make sure the title field is pre-filled with the old title. Other
        // fields will be tested in testQuizFields().
        $this->assertEquals($old_title, $this->editPage->qaForm->title->getContent());

        // Set the new title and save.
        $this->editPage->qaForm->title->fill($new_title);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Make sure the quiz's new title was saved by checking the title
        // displayed in the quiz overview table.
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $this->assertEquals($new_title, $row->title);

        // Generate a random e-mail address to use when participating in all
        // three quizzes.
        $email_provider = new EmailTestDataProvider();
        $email = $email_provider->getValidValue();

        // Loop over each quiz.
        foreach ($quizzes_data as $quiz_index => $quiz_data) {
            $qid = $quiz_data['qid'];

            // Publish the quiz.
            $this->configurePage->go();
            $row = $this->configurePage->quizTable->getRowByQid($qid);
            $row->status->check();
            $this->configurePage->contextualToolbar->buttonSave->click();
            $this->waitUntilTextIsPresent('The changes have been saved.');

            // Verify that there's no export link for the quiz.
            $row = $this->configurePage->quizTable->getRowByQid($qid);
            $this->assertFalse($row->isExportLinkPresent());

            // Create a quiz page for the quiz and publish it.
            $nid = $this->contentCreationService->createQuizPageViaUI(null, $qid);
            $this->adminNodeView->contextualToolbar->buttonPublish->click();
            $this->adminNodeView->checkArrival();

            // Go to the quiz page on the front end.
            $node_view = new QuizViewPage($this);
            $node_view->go(array($nid));

            // Determine the quizzes correct answers.
            $answers = array();
            foreach ($quiz_data['questions'] as $question_data) {
                $answers[] = $question_data['correct_answer'];
            }

            // Participate in the quiz.
            QuizService::participateViaUI($this, $node_view, $quiz_data, array(
                'email' => $email,
                'answers' => $answers,
                'tiebreaker' => $this->random->getValidValue(8),
            ));

            // Make sure the participation has been stored.
            $participations = QuizService::getParticipations($qid, $email);
            $this->assertCount(1, $participations);

            // Go to the configuration page and make sure there's now an export
            // link for the quiz's results.
            $this->configurePage->go();
            $row = $this->configurePage->quizTable->getRowByQid($qid);
            $this->assertTrue($row->isExportLinkPresent());

            // Click the export link but cancel the export.
            $row->exportLink->click();
            $export_modal = new ExportModal($this);
            $export_modal->waitUntilOpened();
            $export_modal->form->cancelButton->click();
            $export_modal->waitUntilClosed();

            // Make sure there's no message saying that an export will be
            // generated.
            $export_message = 'The export for quiz ' . $quiz_data['title'] . ' is being prepared and you will be notified soon.';
            $this->assertTextNotPresent($export_message);

            // Click the export link again but this time confirm the export, and
            // make sure the user is notified that an export will be generated.
            $row->exportLink->click();
            $export_modal->waitUntilOpened();
            $export_modal->form->exportButton->click();
            $export_modal->waitUntilClosed();
            $this->waitUntilTextIsPresent($export_message);
        }

        // Go back to the configuration page.
        $this->configurePage->go();

        // Get the first quiz we created from the table.
        $delete_qid = $quizzes_data[0]['qid'];
        $row = $this->configurePage->quizTable->getRowByQid($delete_qid);

        // Click its delete link but cancel afterwards.
        $row->deleteLink->click();
        $modal = new ConfirmationModal($this);
        $modal->waitUntilOpened();
        $modal->form->cancelButton->click();
        $modal->waitUntilClosed();

        // Now actually delete the quiz.
        $row = $this->configurePage->quizTable->getRowByQid($delete_qid);
        $row->deleteLink->click();
        $modal->waitUntilOpened();
        $modal->form->deleteButton->click();
        $modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('The quiz ' . $quizzes_data[0]['title'] . ' has been deleted.');

        // Make sure the quiz is no longer present in the table.
        $this->assertFalse($this->configurePage->quizTable->getRowByQid($delete_qid));

        // Make sure the quiz's participations have also been deleted.
        $participations = QuizService::getParticipations($delete_qid);
        $this->assertCount(0, $participations);

        // Check that the other quizzes' participations have not been deleted.
        for ($i = 1; $i < count($quizzes_data); $i++) {
            $qid = $quizzes_data[$i]['qid'];
            $participations = QuizService::getParticipations($qid);
            $this->assertCount(1, $participations);
        }
    }

    /**
     * Tests the preview step on the add/edit pages and the admin node view.
     */
    public function testPreview()
    {
        // Get the count of all participation records in the database.
        $participation_count = count(QuizService::getParticipations());

        // Create a new quiz with only a title and some questions and answers.
        $data = QuizService::generateRandomData($this);
        $this->configurePage->go();
        $qids = $this->configurePage->quizTable->getQids();
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $this->addPage->checkArrival();
        QuizFormFiller::fillQaForm($this->addPage->qaForm, $data);

        // Go straight to the preview step.
        $this->moveto($this->addPage->qaForm->previewStepButton);
        // Scroll down to avoid the admin panel.
        $this->execute(
            array(
                'script' => "scrollBy(0, -350);",
                'args' => array(),
            )
        );
        $this->addPage->qaForm->previewStepButton->click();
        $this->addPage->checkArrival();

        // Make sure the quiz is functional.
        $answers = array();
        foreach ($data['questions'] as $question_data) {
            $answers[] = $question_data['correct_answer'];
        }
        $email_provider = new EmailTestDataProvider();
        QuizService::participateViaUI($this, $this->addPage, $data, array(
            'email' => $email_provider->getValidValue(),
            'answers' => $answers,
        ));

        // Make sure the participation count has not increased.
        $this->assertCount($participation_count, QuizService::getParticipations());

        // Go back to the configuration page and get the qid of the new quiz.
        $this->configurePage->go();
        $new_qids = $this->configurePage->quizTable->getQids();
        $new_qids = array_values(array_diff($new_qids, $qids));
        $qid = $new_qids[0];

        // Publish the quiz.
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $row->status->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Click the edit button of the quiz on the configuration page.
        $row = $this->configurePage->quizTable->getRowByQid($qid);
        $row->editLink->click();

        // Go to the customize step and update the quiz.
        $this->editPage->checkArrival();
        $this->editPage->qaForm->customizeStepButton->click();
        $this->editPage->checkArrival();
        QuizFormFiller::fillCustomizeForm($this->editPage->customizeForm, $data);

        // Go to the preview step and make the latest version of the quiz is
        // shown.
        $this->moveto($this->editPage->customizeForm->previewStepButton);
        // Scroll down to avoid the admin panel.
        $this->execute(
            array(
                'script' => "scrollBy(0, -350);",
                'args' => array(),
            )
        );
        $this->editPage->customizeForm->previewStepButton->click();
        $this->editPage->checkArrival();
        $this->assertEquals($data['customize']['startTitle'], $this->editPage->quizForm->startScreen->title);

        // Click the start button and go to the next step.
        $previewForm = $this->editPage->quizForm;
        $previewForm->startScreen->nextButton->click();
        $previewForm->waitUntilScreenIsVisible('question');

        // Refresh the page and make sure we're back on the start screen. (The
        // preview should not remember the state of the quiz participation.)
        $this->editPage->go(array($qid, 'preview'));
        $this->assertEquals('start', $this->editPage->quizForm->currentScreenName);
        $this->assertTextPresent($data['customize']['startTitle']);

        // Go through the whole quiz and make sure the participation count has
        // not increased afterwards.
        QuizService::participateViaUI($this, $this->editPage, $data, array(
            'email' => $email_provider->getValidValue(),
            'answers' => $answers,
            'tiebreaker' => $this->random->getValidValue(8),
        ));
        $this->assertCount(0, QuizService::getParticipations($qid));

        // Create a quiz page with the quiz we created.
        $title = $this->random->getValidValue(8);
        $nid = $this->contentCreationService->createQuizPageViaUI($title, $qid);

        // Go to the quiz page's admin node view.
        $this->adminNodeView->go(array($nid));

        // "Participate" in the quiz on the admin node view, and make sure the
        // participation count is still zero as the quiz on the admin node view
        // is also a preview.
        QuizService::participateViaUI($this, $this->adminNodeView, $data, array(
            'email' => $email_provider->getValidValue(),
            'answers' => $answers,
            'tiebreaker' => $this->random->getValidValue(8),
        ));
        $this->assertCount(0, QuizService::getParticipations($qid));
    }

    /**
     * Tests the fields in the multi-step form for adding / editing a quiz.
     */
    public function testQuizFields()
    {
        // Generate some data to use in the quiz fields. We need to do this
        // first as creating the image atom will take us away from our current
        // page.
        $data = QuizService::generateRandomData($this);

        // Create a new quiz with the random data.
        $qid = QuizService::createViaUI($this, $data, true, true);

        // Go to its edit page.
        $this->editPage->go(array($qid));
        $form = $this->editPage->qaForm;

        // Verify that the title is still filled in correctly.
        $this->assertEquals($data['title'], $form->title->getContent());

        // Make sure the questions and answers are pre-filled correctly.
        $this->assertQuestionsAndAnswers($form, $data['questions']);

        // Skip to the design step using the breadcrumbs at the top of the form.
        $form->designStepButton->click();
        $this->editPage->checkArrival();
        $form = $this->editPage->designForm;

        // Verify that the start page image is set correctly.
        $this->assertEquals($data['design']['startImage']['id'], $form->startImage->valueField->value());

        // Go back a step (to the customize form), using the breadcrumbs again.
        $form->customizeStepButton->click();
        $this->editPage->checkArrival();
        $form = $this->editPage->customizeForm;

        // Verify that the tiebreaker checkbox is still selected.
        $this->assertTrue($form->tiebreaker->isChecked());

        // Loop over all text and wysiwyg fields and ensure their values are
        // the same as when we created the quiz.
        foreach ($data['customize'] as $field => $value) {
            // Wysiwyg fields use getBodyText() instead of getContent().
            if ($form->{$field} instanceof Wysiwyg) {
                // CKEditor and the text formats usually wrap the body text in a
                // HTML tag like <p>, but this may change in the future, so we
                // should check that the body text at least contains our original
                // value.
                $this->assertContains($value, $form->{$field}->getBodyText());
            } else {
                $this->assertEquals($value, $form->{$field}->getContent());
            }
        }

        // Verify that the location of the info page is set correctly.
        $this->assertFalse($form->infoLocationStart->isSelected());
        $this->assertTrue($form->infoLocationEnd->isSelected());

        // Verify that the necessary user info is set to "name + email".
        $this->assertFalse($form->infoEmail->isSelected());
        $this->assertTrue($form->infoNameAndEmail->isSelected());

        // Go back to the "questions" step using the breadcrumb.
        $form->qaStepButton->click();
        $this->editPage->checkArrival();
        $form = $this->editPage->qaForm;

        // Remove the first question.
        $form->removeQuestion(0);
        unset($data['questions'][0]);
        $data['questions'] = array_values($data['questions']);

        // Save the quiz, and go back to the edit page afterwards.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->editPage->go(array($qid));
        $form = $this->editPage->qaForm;

        // Verify that the questions on the form no longer contain the first
        // question.
        $this->assertQuestionsAndAnswers($form, $data['questions']);

        // Set the correct answer of the (new) first question to the second
        // answer (index 1).
        $question = $form->getQuestion(0);
        $question->indicateCorrectAnswer(1);

        // Empty the text field of the new correct answer.
        $question->emptyAnswer(1);
        unset($data['questions'][0]['answers'][1]);
        $data['questions'][0]['answers'] = array_values($data['questions'][0]['answers']);

        // Save the page, ensure we get a validation error because we no longer
        // have a correct answer.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Please indicate a correct answer for question 1.');
        $form = $this->editPage->qaForm;

        // Set the third answer as the correct one. (The second answer is empty
        // but its text field is still present on the form as there was a
        // validation error.)
        $form->getQuestion(0)->indicateCorrectAnswer(2);

        // Save the quiz, and go back to the edit page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->editPage->go(array($qid));
        $form = $this->editPage->qaForm;

        // Ensure the 2nd answer is deleted, and that the new 2nd answer (which
        // used to be the 3rd answer) is indicated as the correct answer.
        $data['questions'][0]['correct_answer'] = 1;
        $this->assertQuestionsAndAnswers($form, $data['questions']);
    }

    /**
     * Asserts that the questions and answers in a QuizQaForm are set correctly.
     *
     * @param QuizQaForm $form
     *   Form on the page.
     * @param array $questions_data
     *   List of arrays with the following keys:
     *      - title: String.
     *      - image: Image data array as provided by AssetCreationService.
     *      - answers: Array of strings.
     *      - correct_answer: Int, index of the correct answer.
     */
    protected function assertQuestionsAndAnswers(QuizQaForm $form, $questions_data)
    {
        // Loop over the questions and answers and verify that they're filled in
        // correctly.
        foreach ($questions_data as $index => $question_data) {
            $question = $form->getQuestion($index);

            // Verify title and image values.
            $this->assertEquals($question_data['title'], $question->title->getContent());
            $this->assertEquals($question_data['image']['id'], $question->image->valueField->value());

            // Verify answer (text) values.
            foreach ($question_data['answers'] as $answer_index => $answer) {
                $this->assertEquals($answer, $question->getAnswer($answer_index)->getContent());
            }

            // Verify that the correct radio button is selected to indicate the
            // correct answer.
            foreach ($question->correctAnswerRadioButtons as $radio_index => $radio) {
                if ($radio_index == $question_data['correct_answer']) {
                    $this->assertTrue($radio->isSelected());
                } else {
                    $this->assertFalse($radio->isSelected());
                }
            }
        }
    }
}
