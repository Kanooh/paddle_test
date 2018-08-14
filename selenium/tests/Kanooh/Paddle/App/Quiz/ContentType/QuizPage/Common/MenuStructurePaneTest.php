<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;

/**
 * Class MenuStructurePaneTest
 * @package Kanooh\Paddle\Core\ContentType\QuizPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MenuStructurePaneTest extends MenuStructurePaneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Quiz);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        $service = new ContentCreationService($this, $this->userSessionService);
        return $service->createQuizPageViaUI($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPage()
    {
        return new LayoutPage($this);
    }
}
