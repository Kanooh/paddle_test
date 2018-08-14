<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CookieLegislation\InfoTest.
 */

namespace Kanooh\Paddle\App\CookieLegislation;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\CookieLegislation;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\CookieLegislation
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
        return new CookieLegislation;
    }
}
