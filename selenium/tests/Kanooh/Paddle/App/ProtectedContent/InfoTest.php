<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ProtectedContent\InfoTest.
 */

namespace Kanooh\Paddle\App\ProtectedContent;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\ProtectedContent;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\ProtectedContent
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
        return new ProtectedContent;
    }
}
