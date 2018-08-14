<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\IncomingRSS\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\IncomingRSS;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\IncomingRSS;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\IncomingRSS
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
        return new IncomingRSS;
    }

    /**
     * @inheritDoc
     *
     * @group KANWEBS-4686
     */
    public function testEnableDisable()
    {
        parent::testEnableDisable();
    }
}
