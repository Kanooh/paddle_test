<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cultuurnet\InfoTest.
 */

namespace Kanooh\Paddle\App\Cultuurnet;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Cultuurnet;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Cultuurnet
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
        return new Cultuurnet;
    }
}
