<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\ContentType\News\Common\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\News\ContentType\News\Common;

use Kanooh\Paddle\Core\ContentType\Base\ListingPaneTestBase;

/**
 * Class ListingPaneTest
 * @package Kanooh\Paddle\App\News\ContentType\News\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListingPaneTest extends ListingPaneTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createNewsItem($title);
    }
}
