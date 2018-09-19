<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\NodeDeleteButtonTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\NodeDeleteButtonTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
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

        $this->appService->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItem($title);
    }
}
