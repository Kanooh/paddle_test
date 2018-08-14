<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Carousel\InfoTest.
 */

namespace Kanooh\Paddle\App\Carousel;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Carousel;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Carousel
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
        return new Carousel;
    }
}
