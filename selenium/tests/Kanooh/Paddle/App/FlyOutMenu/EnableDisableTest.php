<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FlyOutMenu\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\FlyOutMenu;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\FlyOutMenu;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\FlyOutMenu
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
        return new FlyOutMenu;
    }
}
