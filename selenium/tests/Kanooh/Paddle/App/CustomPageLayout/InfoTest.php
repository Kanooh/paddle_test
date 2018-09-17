<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomPageLayout\InfoTest.
 */

namespace Kanooh\Paddle\App\CustomPageLayout;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\CustomPageLayout;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\CustomPageLayout
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
        return new CustomPageLayout;
    }
}
