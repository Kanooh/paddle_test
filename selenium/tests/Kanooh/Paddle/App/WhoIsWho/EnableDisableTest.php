<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\WhoIsWho\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\WhoIsWho;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\WhoIsWho;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\WhoIsWho
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
        return new WhoIsWho;
    }
}
