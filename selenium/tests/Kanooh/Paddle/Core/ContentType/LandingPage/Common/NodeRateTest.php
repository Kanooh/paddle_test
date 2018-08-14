<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\NodeRateTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

use Kanooh\Paddle\App\Rate\ContentType\Base\NodeRateTestBase;

/**
 * NodeRateTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRateTest extends NodeRateTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createLandingPage(null, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'landing_page';
    }
}
