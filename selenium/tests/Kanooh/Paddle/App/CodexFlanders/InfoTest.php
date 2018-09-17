<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CodexFlanders\InfoTest.
 */

namespace Kanooh\Paddle\App\CodexFlanders;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\CodexFlanders;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\CodexFlanders
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
        return new CodexFlanders;
    }
}
