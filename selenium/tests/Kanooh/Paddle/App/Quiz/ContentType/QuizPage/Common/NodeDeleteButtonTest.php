<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common\NodeDeleteButtonTest.
 */

namespace Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Core\ContentType\Base\NodeDeleteButtonTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\Core\ContentType\QuizPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeDeleteButtonTest extends NodeDeleteButtonTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Quiz);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createQuizPage($title);
    }
}
