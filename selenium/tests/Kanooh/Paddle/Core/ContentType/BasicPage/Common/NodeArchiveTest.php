<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

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
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
