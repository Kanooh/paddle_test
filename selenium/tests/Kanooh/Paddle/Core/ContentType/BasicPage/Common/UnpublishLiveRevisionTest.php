<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\UnpublishLiveRevisionTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase;
use Kanooh\Paddle\Utilities\ContentCreationService;

/**
 * UnpublishLiveRevisionTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTest extends UnpublishLiveRevisionTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
