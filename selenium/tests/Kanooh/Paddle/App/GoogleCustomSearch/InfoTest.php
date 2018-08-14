<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\InfoTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\GoogleCustomSearch;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\GoogleCustomSearch
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
        return new GoogleCustomSearch;
    }
}
