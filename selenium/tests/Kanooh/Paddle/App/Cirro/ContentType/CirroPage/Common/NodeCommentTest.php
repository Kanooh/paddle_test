<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\App\Comment\ContentType\Base\NodeCommentTestBase;
use Kanooh\Paddle\Apps\Cirro;

/**
 * NodeCommentTest class.
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

        $this->appService->enableApp(new Cirro);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCirroPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_cirro_page';
    }
}
