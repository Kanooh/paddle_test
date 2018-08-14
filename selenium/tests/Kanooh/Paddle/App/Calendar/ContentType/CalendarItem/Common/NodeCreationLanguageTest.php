<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\NodeCreationLanguageTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase;

/**
 * Class NodeDeleteButtonTest
 * @package Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCreationLanguageTest extends NodeCreationLanguageTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Calendar);
    }

    /**
     * {@inheritdoc}
     */
    public function getModalClassName()
    {
        return '\Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateCalendarItemModal';
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'calendar_item';
    }
}
