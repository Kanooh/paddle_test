<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\OrganizationalUnit;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\OrganizationalUnit
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
        return new OrganizationalUnit;
    }
}
