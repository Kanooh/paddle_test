<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\Product\ContentType\Product\Common;

use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMenuItemListTest
 * @package Kanooh\Paddle\App\Product\ContentType\Product\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
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
    public function getContentTypeName()
    {
        return 'paddle_product';
    }
}
