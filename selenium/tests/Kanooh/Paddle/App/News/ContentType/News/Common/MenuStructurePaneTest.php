<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class MenuStructurePaneTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
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
        $service->enableApp(new News);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItem($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPage()
    {
        return new LayoutPage($this);
    }
}
