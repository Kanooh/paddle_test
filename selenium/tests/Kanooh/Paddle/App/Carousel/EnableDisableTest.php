<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Carousel\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Carousel;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Carousel;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Carousel
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
        return new Carousel;
    }
}
