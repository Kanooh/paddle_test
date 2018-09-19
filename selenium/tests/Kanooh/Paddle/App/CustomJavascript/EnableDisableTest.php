<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomJavascript\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\CustomJavascript;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\CustomJavascript;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\CustomJavascript
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
        return new CustomJavascript;
    }
}
