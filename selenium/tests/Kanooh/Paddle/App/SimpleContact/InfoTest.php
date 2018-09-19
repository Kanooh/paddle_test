<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\InfoTest.
 */

namespace Kanooh\Paddle\App\SimpleContact;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\SimpleContact;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\SimpleContact
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
        return new SimpleContact;
    }
}
