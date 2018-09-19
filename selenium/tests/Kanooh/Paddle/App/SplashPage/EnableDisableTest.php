<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SplashPage\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\SplashPage;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\SplashPage;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\SplashPage
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
        return new SplashPage;
    }

    /**
     * Test enabling and disabling the Splash page app
     *
     * @group enableDisableApp
     */
    public function testEnableDisable()
    {
        // We are overwriting the base enable disable test since this paddlet is not available in paddle store.
        // @TODO add the info test back and rollback this change once the splash page paddlet is available in the paddle store.
        // Install the app.
        $this->appService->enableApp($this->app);
        drupal_cron_run();
        $this->assertTrue(module_exists($this->app->getModuleName()));
        $this->appService->disableApp($this->app);
        $this->assertFalse(module_exists($this->app->getModuleName()));
    }
}
