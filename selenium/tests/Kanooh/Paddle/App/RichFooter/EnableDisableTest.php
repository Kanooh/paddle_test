<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\RichFooter\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\RichFooter;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\RichFooter;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\RichFooter
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
        return new RichFooter;
    }
}
