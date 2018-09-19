<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\AdvancedSearch;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\AdvancedSearch
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
        return new AdvancedSearch;
    }
}
