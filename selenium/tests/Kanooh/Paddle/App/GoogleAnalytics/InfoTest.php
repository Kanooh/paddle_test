<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleAnalytics\InfoTest.
 */

namespace Kanooh\Paddle\App\GoogleAnalytics;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\GoogleAnalytics;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\GoogleAnalytics
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
        return new GoogleAnalytics;
    }
}
