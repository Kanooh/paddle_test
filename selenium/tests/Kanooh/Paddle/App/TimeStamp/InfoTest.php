<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Timestamp\InfoTest.
 */

namespace Kanooh\Paddle\App\TimeStamp;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\TimeStamp;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\TimeStamp
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
        return new TimeStamp;
    }
}
