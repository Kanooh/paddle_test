<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common\NodeDetailTest.
 */

namespace Kanooh\Paddle\App\HolidayParticipation\ContentType\Offer\Common;

use Kanooh\Paddle\Core\ContentType\Base\NodeDetailTestBase;

/**
 * Class NodeDetailTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeDetailTest extends NodeDetailTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOfferPage($title);
    }
}
