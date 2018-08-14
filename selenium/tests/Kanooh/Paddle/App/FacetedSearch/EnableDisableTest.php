<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FacetedSearch\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\FacetedSearch;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\FacetedSearch;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\FacetedSearch
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
        return new FacetedSearch;
    }
}
