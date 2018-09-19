<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FlyOutMenu\InfoTest.
 */

namespace Kanooh\Paddle\App\FlyOutMenu;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\FlyOutMenu;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\FlyOutMenu
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
        return new FlyOutMenu;
    }
}
