<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\MenuStructurePaneTest.
 */

namespace Kanooh\Paddle\App\Product\ContentType\Product\Common;

use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Core\ContentType\Base\MenuStructurePaneTestBase;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class MenuStructurePaneTest
 * @package Kanooh\Paddle\App\Product\ContentType\Product\Common
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
        $service->enableApp(new Product);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createProductPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutPage()
    {
        return new LayoutPage($this);
    }
}
