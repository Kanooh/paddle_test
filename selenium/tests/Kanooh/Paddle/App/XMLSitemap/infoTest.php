<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\XMLSiteMap\InfoTest.
 */

namespace Kanooh\Paddle\App\XMLSiteMap;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\XMLSiteMap;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\XMLSiteMap
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
        return new XMLSiteMap();
    }
}
