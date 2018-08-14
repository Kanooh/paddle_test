<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Timestamp\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\TimeStamp;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\TimeStamp;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\TimeStamp
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
        return new TimeStamp;
    }
}
