<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ExternalLinks\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\ExternalLinks;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\ExternalLinks;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\ExternalLinks
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
        return new ExternalLinks;
    }
}
