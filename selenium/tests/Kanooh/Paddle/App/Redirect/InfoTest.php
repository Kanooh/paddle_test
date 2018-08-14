<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Redirect\InfoTest.
 */

namespace Kanooh\Paddle\App\Redirect;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Redirect;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Redirect
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
        return new Redirect;
    }
}
