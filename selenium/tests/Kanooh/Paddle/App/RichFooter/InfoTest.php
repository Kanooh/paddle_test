<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\RichFooter\InfoTest.
 */

namespace Kanooh\Paddle\App\RichFooter;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\RichFooter;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\RichFooter
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
        return new RichFooter;
    }
}
