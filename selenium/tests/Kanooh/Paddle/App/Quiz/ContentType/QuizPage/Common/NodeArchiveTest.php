<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Quiz);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createQuizPageViaUI($title);
    }
}
