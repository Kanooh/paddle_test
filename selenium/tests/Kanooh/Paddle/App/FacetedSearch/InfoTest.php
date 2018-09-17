<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\FacetedSearch\InfoTest.
 */

namespace Kanooh\Paddle\App\FacetedSearch;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\FacetedSearch;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\FacetedSearch
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
        return new FacetedSearch;
    }
}
