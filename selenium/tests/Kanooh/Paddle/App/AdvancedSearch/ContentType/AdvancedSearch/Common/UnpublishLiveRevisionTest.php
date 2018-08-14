<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common\UnpublishLiveRevisionTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\ContentType\AdvancedSearch\Common;

use Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTest extends UnpublishLiveRevisionTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createAdvancedSearchPage($title);
    }
}
