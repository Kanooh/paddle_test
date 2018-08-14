<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\PageInformationTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\PageInformationTestBase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PageInformationTest extends PageInformationTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createLandingPage(null, $title);
    }
}
