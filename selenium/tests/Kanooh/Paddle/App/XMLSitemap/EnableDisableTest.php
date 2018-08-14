<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\XMLSiteMap\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\XMLSiteMap;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\XMLSiteMap;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\XMLSiteMap
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
        return new XMLSiteMap;
    }
}
