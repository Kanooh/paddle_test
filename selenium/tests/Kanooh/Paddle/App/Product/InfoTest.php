<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\InfoTest.
 */

namespace Kanooh\Paddle\App\Product;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Product;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Product
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
        return new Product;
    }
}
