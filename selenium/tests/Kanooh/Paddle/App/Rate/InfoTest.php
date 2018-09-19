<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\InfoTest.
 */

namespace Kanooh\Paddle\App\Rate;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Rate;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Rate
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
        return new Rate;
    }
}
