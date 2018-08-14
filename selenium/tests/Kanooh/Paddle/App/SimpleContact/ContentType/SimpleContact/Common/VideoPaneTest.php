<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common;

use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\SimpleContact\ContentType\SimpleContact\Common
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
        return $this->contentCreationService->createSimpleContact($title);
    }
}
