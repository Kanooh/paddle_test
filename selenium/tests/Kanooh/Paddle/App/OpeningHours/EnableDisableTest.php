<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\OpeningHours;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\OpeningHours;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\OpeningHours
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
        return new OpeningHours;
    }
}
