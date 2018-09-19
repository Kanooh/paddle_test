<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CodexFlanders\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\CodexFlanders;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\CodexFlanders;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\CodexFlanders
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
        return new CodexFlanders();
    }
}
