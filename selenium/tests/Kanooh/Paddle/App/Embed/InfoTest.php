<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Embed\InfoTest.
 */

namespace Kanooh\Paddle\App\Embed;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\Embed;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\Embed
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
        return new Embed;
    }
}
