<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomJavascript\InfoTest.
 */

namespace Kanooh\Paddle\App\CustomJavascript;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\CustomJavascript;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\CustomJavascript
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
        return new CustomJavascript;
    }
}
