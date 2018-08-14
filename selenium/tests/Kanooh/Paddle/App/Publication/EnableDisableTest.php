<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Publication;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Publication;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Publication
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
        return new Publication;
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
        // @TODO add the info test back and rollback this change once the publications paddlet is available in the paddle store.
        // Install the app.
        $this->appService->enableApp($this->app);
        drupal_cron_run();
        $this->assertTrue(module_exists('paddle_publication'));
        $this->appService->disableApp($this->app);
        $this->assertFalse(module_exists('paddle_publication'));
    }
}
