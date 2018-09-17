<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'paddle_overview_page';
    }
}
