<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\NodeMetadataSummaryTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeMetadataSummaryTestBase;

/**
 * Class NodeMetadataSummaryTest
 * @package Kanooh\Paddle\Core\ContentType\OverviewPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMetadataSummaryTest extends NodeMetadataSummaryTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }
}
