<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MegaDropDown\InfoTest.
 */

namespace Kanooh\Paddle\App\MegaDropDown;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\MegaDropdown;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\MegaDropDown
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
        return new MegaDropdown;
    }
}
