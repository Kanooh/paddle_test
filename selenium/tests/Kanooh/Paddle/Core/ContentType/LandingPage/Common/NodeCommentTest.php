<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

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
        return $this->contentCreationService->createLandingPage(null, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'landing_page';
    }
}
