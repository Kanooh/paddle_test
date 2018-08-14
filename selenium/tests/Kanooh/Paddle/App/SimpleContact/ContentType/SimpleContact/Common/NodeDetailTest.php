<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\NodeDetailTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeDetailTestBase;

/**
 * Class NodeDetailTest
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
        return $this->contentCreationService->createSimpleContact($title);
    }
}
