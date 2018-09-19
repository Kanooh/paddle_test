<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialMedia\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\SocialMedia;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\SocialMedia;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\SocialMedia
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
        return new SocialMedia;
    }
}
