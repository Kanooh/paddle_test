<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Rate;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Rate;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Rate
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
        return new Rate;
    }
}
