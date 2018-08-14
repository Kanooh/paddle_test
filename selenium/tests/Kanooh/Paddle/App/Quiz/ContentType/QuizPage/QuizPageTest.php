<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\QuizPageTest.
 */

namespace Kanooh\Paddle\App\Quiz;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\QuizPage as EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\QuizService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider as Random;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class QuizPageTest
 * @package Kanooh\Paddle\App\Quiz
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class QuizPageTest extends WebDriverTestCase
{
    /**
     * Administrative node view page.
     *
     * @var AdministrativeViewPage
     */
    protected $administrativeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Node edit page.
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
     * Node view page on the front-end.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some classes for the tests.
        $this->administrativeViewPage = new AdministrativeViewPage($this);
        $this->editPage = new EditPage($this);
        $this->viewPage = new ViewPage($this);

        $this->random = new Random();

        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the Quiz app.
        $this->appService->enableApp(new Quiz());

        // Bootstrap Drupal.
        $drupal = new DrupalService();
        if (!$drupal->isBootstrapped()) {
            $drupal->bootstrap($this);
        }
    }

    /**
     * Tests the placeholder for unpublished/deleted quizzes.
     */
    public function testUnavailableQuizText()
    {
        // Login as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create an unpublished quiz.
        $quiz = QuizService::create();

        // Create a quiz page via the UI using that quiz entity.
        $page_title = $this->random->getValidValue(8);
        $page_nid = $this->contentCreationService->createQuizPageViaUI($page_title, $quiz->qid);

        // Preview the quiz page.
        $this->administrativeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->viewPage->waitUntilPageIsLoaded();

        // Make sure there's a placeholder text saying that the quiz is not
        // available (as it's not published yet).
        $placeholder = 'This quiz is no longer available. We apologize for any inconvenience that this may cause.';
        $this->assertTextPresent($placeholder);

        // The quiz should not be visible (right now it only shows the quiz
        // title if the quiz is visible).
        $this->assertTextNotPresent($quiz->title);

        // Publish the quiz.
        $quiz->status = 1;
        $quiz->save();

        // Refresh the page. Don't use reloadPage() as it doesn't know the nid
        // yet. (We didn't use go() to get here.)
        $this->viewPage->go($page_nid);

        // Make sure the placeholder text is not present after publishing the
        // quiz.
        $this->assertTextNotPresent($placeholder);

        // Make sure the quiz is now visible.
        $this->assertTextPresent($quiz->title);

        // Delete the quiz.
        QuizService::delete($quiz->qid);

        // Refresh the page.
        $this->viewPage->reloadPage();

        // Make sure the placeholder text is visible again.
        $this->assertTextPresent($placeholder);

        // Make sure the quiz is not visible.
        $this->assertTextNotPresent($quiz->title);
    }

    /**
     * Tests the editing of a quiz page to select another quiz.
     */
    public function testQuizReferenceField()
    {
        // Login as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create a published quiz.
        $quiz = QuizService::create(array('status' => 1));

        // Create a quiz page with that quiz.
        $page_title = $this->random->getValidValue(8);
        $page_nid = $this->contentCreationService->createQuizPageViaUI($page_title, $quiz->qid);

        // Preview the quiz page.
        $this->administrativeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->viewPage->waitUntilPageIsLoaded();

        // Make sure the quiz title is correct.
        $this->assertTextPresent($quiz->title);

        // Create another published quiz.
        $new_quiz = QuizService::create(array('status' => 1));

        // Go to the quiz page's edit page.
        $this->editPage->go($page_nid);

        // Make sure the selected quiz in the reference field is the first quiz
        // we created.
        $selected_qid = $this->editPage->quizPageForm->quizReference->getSelectedQuizId();
        $this->assertEquals($quiz->qid, $selected_qid);

        // Change the quiz reference to the new quiz.
        $this->editPage->quizPageForm->quizReference->select($new_quiz->qid);

        // Save the edit page and preview the quiz page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeViewPage->waitUntilPageIsLoaded();
        $this->administrativeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->viewPage->waitUntilPageIsLoaded();

        // Make sure the new quiz's title is displayed instead of the old one.
        $this->assertTextNotPresent($quiz->title);
        $this->assertTextPresent($new_quiz->title);
    }
}
