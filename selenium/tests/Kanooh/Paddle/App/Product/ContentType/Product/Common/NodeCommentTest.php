<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\ContentType\Product\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\App\Product\ContentType\Product\Common;

use Kanooh\Paddle\Apps\Product;
use Kanooh\Paddle\App\Comment\ContentType\Base\NodeCommentTestBase;

/**
 * Class NodeCommentTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCommentTest extends NodeCommentTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Product);
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
