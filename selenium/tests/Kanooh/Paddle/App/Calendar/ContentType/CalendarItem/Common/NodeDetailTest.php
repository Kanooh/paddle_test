<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\NodeDetailTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

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
        return $this->contentCreationService->createCalendarItem($title);
    }
}
