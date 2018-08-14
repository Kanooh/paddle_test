<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SectionTheming\InfoTest.
 */

namespace Kanooh\Paddle\App\SectionTheming;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\SectionTheming;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\SectionTheming
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
        return new SectionTheming;
    }
}
