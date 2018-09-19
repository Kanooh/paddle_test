<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PaneCollection\InfoTest.
 */

namespace Kanooh\Paddle\App\PaneCollection;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\PaneCollection;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\PaneCollection
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
        return new PaneCollection;
    }
}
