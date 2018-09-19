<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialIdentities\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\SocialIdentities;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\SocialIdentities;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\SocialIdentities
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
        return new SocialIdentities;
    }
}
