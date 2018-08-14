<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\NodeLockingTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\NodeLockingTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeLockingTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeLockingTest extends NodeLockingTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItem($title);
    }
}
