<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
{
    /**
     * {@inheritdoc}
     */
    protected $contentTypeIsDeletable = false;

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }
}
