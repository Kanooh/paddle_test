<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialMedia\InfoTest.
 */

namespace Kanooh\Paddle\App\SocialMedia;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\SocialMedia;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\SocialMedia
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
        return new SocialMedia;
    }
}
