<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ReCaptcha\InfoTest.
 */

namespace Kanooh\Paddle\App\Recaptcha;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\ReCaptcha;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\ReCaptcha
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
        return new ReCaptcha();
    }
}
