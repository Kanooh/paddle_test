<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ExternalLinks\InfoTest.
 */

namespace Kanooh\Paddle\App\ExternalLinks;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\ExternalLinks;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\ExternalLinks
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
        return new ExternalLinks;
    }
}
