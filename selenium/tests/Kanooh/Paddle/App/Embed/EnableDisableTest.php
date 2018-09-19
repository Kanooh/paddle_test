<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Embed\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Embed;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Embed;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Embed
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
        return new Embed;
    }
}
