<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Poll;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Product
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
        return new Poll;
    }
}
