<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Redirect\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Redirect;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Redirect;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Redirect
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
        return new Redirect;
    }
}
