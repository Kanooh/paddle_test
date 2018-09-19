<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\Product\ContentType\Product\Common;

use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\Product\ContentType\Product\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
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
}
