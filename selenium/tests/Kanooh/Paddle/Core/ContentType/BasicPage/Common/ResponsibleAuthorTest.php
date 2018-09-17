<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\ResponsibleAuthorTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\ResponsibleAuthorTestBase;

/**
 * ResponsibleAuthorTest class.
 *
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
        return $this->contentCreationService->createBasicPage($title);
    }
}
