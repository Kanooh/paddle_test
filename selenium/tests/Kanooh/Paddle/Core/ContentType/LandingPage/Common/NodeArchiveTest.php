<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

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
        return $this->contentCreationService->createLandingPage(null, $title);
    }
}
