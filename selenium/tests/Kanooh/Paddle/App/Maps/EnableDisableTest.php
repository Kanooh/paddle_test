<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Maps;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Maps;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Maps
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
        return new Maps;
    }
}
