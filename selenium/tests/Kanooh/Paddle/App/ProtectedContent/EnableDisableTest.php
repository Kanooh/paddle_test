<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ProtectedContent\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\ProtectedContent;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\ProtectedContent;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\ProtectedContent
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
        return new ProtectedContent;
    }
}
