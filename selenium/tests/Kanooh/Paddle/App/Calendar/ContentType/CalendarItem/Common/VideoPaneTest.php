<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\VideoPaneTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Core\ContentType\Base\VideoPaneBaseTest;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class VideoPaneTest
 * @package Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VideoPaneTest extends VideoPaneBaseTest
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
}
