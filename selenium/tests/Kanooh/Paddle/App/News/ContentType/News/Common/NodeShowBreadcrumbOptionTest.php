<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\NodeShowBreadcrumbOptionTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\NodeShowBreadcrumbOptionTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeShowBreadcrumbOptionTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeShowBreadcrumbOptionTest extends NodeShowBreadcrumbOptionTestBase
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
        return $this->contentCreationService->createNewsItemViaUI($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'news_item';
    }
}
