<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\NodeMenuItemListTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Core\ContentType\Base\NodeMenuItemListTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class NodeMenuItemListTest
 * @package Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeMenuItemListTest extends NodeMenuItemListTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Calendar);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCalendarItem($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'calendar_item';
    }
}
