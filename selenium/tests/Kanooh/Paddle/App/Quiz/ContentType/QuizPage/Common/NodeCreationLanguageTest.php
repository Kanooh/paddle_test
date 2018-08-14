<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateQuizPageModal;
use Kanooh\Paddle\Utilities\QuizService;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\Core\ContentType\QuizPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
{
    /**
     * @var \QuizEntity
     */
    protected $quiz;

    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Quiz);

        // Create a quiz entity to use for node creation.
        $this->quiz = QuizService::create(array('status' => 1));
    }

    /**
     * {@inheritdoc}
     */
    public function getModalClassName()
    {
        return '\Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateQuizPageModal';
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'quiz_page';
    }

    /**
     * {@inheritdoc}
     */
    public function fillInAddModalForm($modal)
    {
        $qid = $this->quiz->qid;
        /** @var CreateQuizPageModal $modal */
        $modal->title->fill($this->alphanumericTestDataProvider->getValidValue());
        $modal->quizReference->select($qid);
    }
}
