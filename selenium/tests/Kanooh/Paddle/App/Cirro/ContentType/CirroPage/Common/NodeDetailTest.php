<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common\NodeDetailTest.
 */

namespace Kanooh\Paddle\App\Cirro\ContentType\CirroPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeDetailTestBase;
use Kanooh\Paddle\Utilities\ContentCreationService;

/**
 * NodeDetailTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeDetailTest extends NodeDetailTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCirroPage($title);
    }
}
