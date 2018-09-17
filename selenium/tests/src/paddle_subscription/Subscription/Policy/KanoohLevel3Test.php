<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\KanoohLevel3Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3;
use Drupal\Tests\paddle_apps\FixedAppRepository;

/**
 * Tests the policy for the most expensive kañooh subscription type.
 */
class KanoohLevel3Test extends PolicyTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->policy = new Level3();
        parent::setUp();
    }

    /**
     * Tests the app install limits of the policy.
     */
    public function testLimits()
    {
        $apps = array();

        // Create 50 free and extra apps and verify no message is shown because
        // there is no limit for this policy.
        for ($i = 0; $i < 50; $i++) {
            $apps[] = $this->createAppWithLevel(App::LEVEL_EXTRA);
            $apps[] = $this->createAppWithLevel(App::LEVEL_FREE);

            // Create a mock App Store using a repository that has the list of
            // created apps as "active" apps.
            $repository = new FixedAppRepository($apps);
            $app_store = new AppStore($this->policy, $repository);

            $this->assertEquals(-1, $this->policy->freeAppsLeft($app_store));
            $this->assertFalse($this->policy->reachingLimit($app_store));
        }
    }
}
