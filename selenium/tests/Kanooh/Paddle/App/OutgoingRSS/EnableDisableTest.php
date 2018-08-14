<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OutgoingRSS\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\OutgoingRSS;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\OutgoingRSS;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\OutgoingRSS
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
        return new OutgoingRSS;
    }
}
