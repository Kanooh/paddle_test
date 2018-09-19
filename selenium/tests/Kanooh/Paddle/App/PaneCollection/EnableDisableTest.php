<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PaneCollection\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\PaneCollection;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\PaneCollection;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\PaneCollection
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
        return new PaneCollection;
    }
}
