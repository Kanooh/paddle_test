<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

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
