<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Formbuilder\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Formbuilder;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Formbuilder;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Formbuilder
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
        return new Formbuilder;
    }
}
