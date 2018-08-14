<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
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
