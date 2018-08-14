<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\InfoTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\ContactPerson;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\ContactPerson
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
        return new ContactPerson;
    }
}
