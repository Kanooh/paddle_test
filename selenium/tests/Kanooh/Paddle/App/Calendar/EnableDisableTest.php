<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Calendar;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Calendar;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Calendar
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new Calendar;
    }
}
