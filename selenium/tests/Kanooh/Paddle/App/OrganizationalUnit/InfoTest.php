<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\InfoTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\OrganizationalUnit;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\OrganizationalUnit
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
        return new OrganizationalUnit;
    }
}
