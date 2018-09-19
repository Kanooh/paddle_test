<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\ContactPerson;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\ContactPerson
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
        return new ContactPerson;
    }
}
