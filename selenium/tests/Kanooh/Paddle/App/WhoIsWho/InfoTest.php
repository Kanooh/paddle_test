<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\WhoIsWho\InfoTest.
 */

namespace Kanooh\Paddle\App\WhoIsWho;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\WhoIsWho;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\WhoIsWho
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
        return new WhoIsWho;
    }
}
