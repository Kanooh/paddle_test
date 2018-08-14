<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\LinkCheckerTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\LinkCheckerTestBase;

/**
 * LinkCheckerTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LinkCheckerTest extends LinkCheckerTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
