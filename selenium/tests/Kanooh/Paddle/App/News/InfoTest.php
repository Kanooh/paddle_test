<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\InfoTest.
 */

namespace Kanooh\Paddle\App\News;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\News;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\News
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
        return new News;
    }
}
