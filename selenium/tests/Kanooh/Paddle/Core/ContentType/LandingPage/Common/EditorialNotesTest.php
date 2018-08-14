<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\LandingPage\Common\EditorialNotesTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage\Common;

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
        return $this->contentCreationService->createLandingPage(null, $title);
    }
}
