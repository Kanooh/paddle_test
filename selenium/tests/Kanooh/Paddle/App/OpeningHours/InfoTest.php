<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\InfoTest.
 */

namespace Kanooh\Paddle\App\OpeningHours;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\OpeningHours;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\OpeningHours
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
        return new OpeningHours;
    }
}
