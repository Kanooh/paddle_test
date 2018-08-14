<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\TaxonomyTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Core\ContentType\Base\TaxonomyTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class TaxonomyTest
 * @package Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TaxonomyTest extends TaxonomyTestBase
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
