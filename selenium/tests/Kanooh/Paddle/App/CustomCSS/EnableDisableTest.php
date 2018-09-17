<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomCSS\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\CustomCSS;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\CustomCSS;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\CustomCSS
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
        return new CustomCSS;
    }
}
