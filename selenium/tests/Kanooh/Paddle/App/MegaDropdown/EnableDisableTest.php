<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MegaDropdown\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\MegaDropdown;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\MegaDropdown;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\MegaDropdown
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
        return new MegaDropdown;
    }
}
