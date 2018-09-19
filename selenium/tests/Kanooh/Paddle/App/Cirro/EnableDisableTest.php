<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Cirro;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Cirro;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Cirro
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
        return new Cirro;
    }

    /**
     * Test enabling and disabling the publication app
     *
     * @group store
     * @group enableDisableApp
     */
    public function testEnableDisable()
    {
        // We are overwriting the base enable disable test since this paddlet is not available in paddle store.
        // We also removed the info test for now.
        // @TODO add the info test back and rollback this change once the cirro paddlet is available in the paddle store.
        // Install the app.
        $this->appService->enableApp($this->app);
        drupal_cron_run();
        $this->assertTrue(module_exists('paddle_cirro'));
        $this->appService->disableApp($this->app);
        $this->assertFalse(module_exists('paddle_cirro'));
    }
}
