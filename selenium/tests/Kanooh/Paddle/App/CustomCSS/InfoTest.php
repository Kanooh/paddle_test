<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomCSS\InfoTest.
 */

namespace Kanooh\Paddle\App\CustomCSS;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\CustomCSS;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\CustomCSS
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
        return new CustomCSS;
    }
}
