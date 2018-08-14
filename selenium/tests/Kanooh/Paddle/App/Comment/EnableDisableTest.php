<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Comment\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Comment;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Comment;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Comment
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
        return new Comment();
    }
}
