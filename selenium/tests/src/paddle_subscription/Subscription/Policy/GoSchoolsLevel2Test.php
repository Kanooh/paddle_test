<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\GoSchoolsLevel2Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level2;
use Drupal\Tests\paddle_apps\FixedAppRepository;

/**
 * Tests the policy for the more expensive GO! schools subscription type.
 */
class GoSchoolsLevel2Test extends PolicyTestBase
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
        // Create some free apps and verify a message saying the limit is almost
        // reached is shown. Go over the limit to verify that the amount of
        // free apps left doesn't roll over to -1, which would indicate
        // unlimited installs.
        $apps = array();
        $extra_apps = array();

        for ($i = 1; $i <= Level2::LIMIT_FREE + 1; $i++) {
            $apps[] = $this->createAppWithLevel(App::LEVEL_FREE);

            // Create a mock App Store using a repository that has the list of
            // created apps as "active" apps.
            $repository = new FixedAppRepository($apps);
            $app_store = new AppStore($this->policy, $repository);


            // Make sure the amount of free apps left lowers after each install,
            // but not under 0 because that would indicate unlimited installs.
            $free_left = Level2::LIMIT_FREE - $i;
            $free_left = $free_left < 0 ? 0 : $free_left;
            $this->assertEquals($free_left, $this->policy->freeAppsLeft($app_store));

            // Once we only can install 3 more apps, the message should show.
            if ($i >= Level2::LIMIT_FREE - 3) {
                $this->assertTrue($this->policy->reachingLimit($app_store));
                // When not within 3 installs away from the limit, do not show
                // the message.
            } else {
                $this->assertFalse($this->policy->reachingLimit($app_store));
            }

            // Test that the limit does not account for extra apps.
            $extra_apps[] = $this->createAppWithLevel(App::LEVEL_EXTRA);
            $repository = new FixedAppRepository($extra_apps);
            $app_store = new AppStore($this->policy, $repository);
            $this->assertFalse($this->policy->reachingLimit($app_store));
        }
    }
}
