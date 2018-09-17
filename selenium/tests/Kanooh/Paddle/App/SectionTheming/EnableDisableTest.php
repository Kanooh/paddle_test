<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SectionTheming\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\SectionTheming;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\SectionTheming;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\SectionTheming
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
        return new SectionTheming;
    }
}
