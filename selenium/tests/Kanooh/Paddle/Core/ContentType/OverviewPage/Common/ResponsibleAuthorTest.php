<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\ResponsibleAuthorTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ResponsibleAuthorTest extends ResponsibleAuthorTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }
}
