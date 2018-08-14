<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\InfoTest.
 */

namespace Kanooh\Paddle\App\Calendar;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Calendar;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Calendar
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InfoTest extends InfoTestBase
{
    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new Calendar;
    }
}
