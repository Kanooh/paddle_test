<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\EditorialNotesTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Kanooh\Paddle\Core\ContentType\Base\EditorialNotesTestBase;

/**
 * EditorialNotesTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EditorialNotesTest extends EditorialNotesTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }
}
