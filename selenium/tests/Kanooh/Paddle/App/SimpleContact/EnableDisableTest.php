<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\SimpleContact;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\SimpleContact;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\SimpleContact
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
        return new SimpleContact;
    }
}
