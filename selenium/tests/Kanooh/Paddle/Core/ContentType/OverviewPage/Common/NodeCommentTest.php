<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

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
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_overview_page';
    }
}
