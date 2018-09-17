<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomPageLayout\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\CustomPageLayout;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\CustomPageLayout;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\CustomPageLayout
 *
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
        return new CustomPageLayout;
    }
}
