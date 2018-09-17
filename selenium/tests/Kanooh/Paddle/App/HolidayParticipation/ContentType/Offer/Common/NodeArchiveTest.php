<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new HolidayParticipation);

        // Removes all imported offers so the archived nodes can be found
        // in the content manager overview when this tests enables de app.
        $this->cleanUpService->deleteEntities('node', 'offer');
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOfferPage($title);
    }
}
