<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\ListingPaneTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Core\ContentType\Base\ListingPaneTestBase;

/**
 * Class ListingPaneTest
 * @package Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common
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
        return $this->contentCreationService->createAdvancedSearchPage($title);
    }
}
