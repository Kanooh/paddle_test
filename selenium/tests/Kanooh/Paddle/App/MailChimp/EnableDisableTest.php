<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\MailChimp\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\MailChimp;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\MailChimp;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\MailChimp
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
        return new MailChimp;
    }
}
