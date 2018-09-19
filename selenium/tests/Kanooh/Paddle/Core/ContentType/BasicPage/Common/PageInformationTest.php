<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\PageInformationTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\PageInformationTestBase;

/**
 * PageInformationTest class.
 *
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
        return $this->contentCreationService->createBasicPage($title);
    }
}
