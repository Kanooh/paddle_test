<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\BasicPage\Common\PaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\ContentType\BasicPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\PaneSectionsTestBase;

/**
 * PaneSectionsTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsTest extends PaneSectionsTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createBasicPage($title);
    }
}
