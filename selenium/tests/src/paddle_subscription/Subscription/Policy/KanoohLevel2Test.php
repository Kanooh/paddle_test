<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\KanoohLevel2Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level2;
use Drupal\Tests\paddle_apps\FixedAppRepository;

/**
 * Tests the policy for the more expensive kañooh subscription type.
 */
class KanoohLevel2Test extends PolicyTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->policy = new Level2();
        parent::setUp();
    }

    /**
     * Tests the app install limits of the policy.
     */
    public function testLimits()
    {
        $apps = array();

        // Create some free apps and verify the message only says something
        // about the number of extra paddlets we can still install.
        for ($i = 0; $i < 50; $i++) {
            $apps[] = $this->createAppWithLevel(App::LEVEL_FREE);

            // Create a mock App Store using a repository that has the list of
            // created apps as "active" apps.
            $repository = new FixedAppRepository($apps);
            $app_store = new AppStore($this->policy, $repository);

            // Amount of free apps left should always be unlimited, and the
            // number of extra apps left to install should not change by
            // installing free apps.
            $this->assertEquals(-1, $this->policy->freeAppsLeft($app_store));

            // Verify that we don't bother the user with a message about extra
            // apps left if there are none installed.
            $this->assertFalse($this->policy->reachingLimit($app_store));

            // Test that there is no limit for extra apps.
            $apps[] = $this->createAppWithLevel(App::LEVEL_EXTRA);
            $repository = new FixedAppRepository($apps);
            $app_store = new AppStore($this->policy, $repository);
            $this->assertFalse($this->policy->reachingLimit($app_store));
        }
    }
}
