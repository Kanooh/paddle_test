<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\KanoohLevel1Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1;
use Drupal\Tests\paddle_apps\FixedAppRepository;

/**
 * Tests the policy for the cheapest kañooh subscription type.
 */
class KanoohLevel1Test extends PolicyTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->policy = new Level1();
        parent::setUp();
    }

    /**
     * Tests the app install limits of the policy.
     */
    public function testLimits()
    {
        // Create some free apps and verify a message saying the limit is almost
        // reached is shown. Go over the limit to verify that the amount of
        // free apps left doesn't roll over to -1, which would indicate
        // unlimited installs.
        $apps = array();
        $extra_apps = array();

        for ($i = 1; $i <= LEVEL1::LIMIT_FREE + 1; $i++) {
            $apps[] = $this->createAppWithLevel(App::LEVEL_FREE);

            // Create a mock App Store using a repository that has the list of
            // created apps as "active" apps.
            $repository = new FixedAppRepository($apps);
            $app_store = new AppStore($this->policy, $repository);


            // Make sure the amount of free apps left lowers after each install,
            // but not under 0 because that would indicate unlimited installs.
            $free_left = 5 - $i;
            $free_left = $free_left < 0 ? 0 : $free_left;
            $this->assertEquals($free_left, $this->policy->freeAppsLeft($app_store));

            // Once we reach 3 or more apps installed, the message should show.
            if ($i >= 3) {
                $this->assertTrue($this->policy->reachingLimit($app_store));
                // When not within 3 installs away from the limit, do not show
                // the message.
            } else {
                $this->assertFalse($this->policy->reachingLimit($app_store));
            }

            // Test that the limit does not account for extra apps.
            $extra_apps[] = $this->createAppWithLevel(App::LEVEL_EXTRA);
            $extra_repository = new FixedAppRepository($extra_apps);
            $extra_app_store = new AppStore($this->policy, $extra_repository);
            $this->assertFalse($this->policy->reachingLimit($extra_app_store));
        }
    }
}
