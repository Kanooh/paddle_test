<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Quiz;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Quiz;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Quiz
 *
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
        return new Quiz;
    }
}
