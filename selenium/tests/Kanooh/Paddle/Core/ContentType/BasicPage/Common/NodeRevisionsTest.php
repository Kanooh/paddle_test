<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\Core\ContentType\BasicPage\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPageViaUI($title);
    }
}
