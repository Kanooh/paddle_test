<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;

/**
 * VideoPaneTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
