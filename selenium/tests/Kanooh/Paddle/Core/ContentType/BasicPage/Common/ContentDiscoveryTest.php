<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;

/**
 * ContentDiscoveryTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentDiscoveryTest extends ContentDiscoveryTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
