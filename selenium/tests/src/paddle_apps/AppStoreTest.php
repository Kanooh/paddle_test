<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_apps\AppStoreTest.
 */

namespace Drupal\Tests\paddle_apps;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;

class AppStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides possible results of the subscription policy.
     *
     * @return array
     *  A list of possible results.
     */
    public function subscriptionPolicyResults()
    {
        return array(
          array(true),
          array(false),
        );
    }

    /**
     * Tests that the app store verifies the subscription policy.
     *
     * @dataProvider subscriptionPolicyResults
     * @param bool $subscriptionPolicyResult
     *   The return value of the subscription policy.
     */
    public function testVerifiesSubscriptionPolicy($subscriptionPolicyResult)
    {
        $app = new App();

        $subscriptionPolicy = $this->getMock('Drupal\\paddle_subscription\\Subscription\\Policy');
        $subscriptionPolicy
          ->expects($this->once())
          ->method('canInstall')
          ->will($this->returnValue($subscriptionPolicyResult));

        $appStore = new AppStore($subscriptionPolicy, new FixedAppRepository());

        $can_install = $appStore->canInstall($app);

        $this->assertEquals($subscriptionPolicyResult, $can_install);
    }

    /**
     * Tests that the app store can filter the active apps by their level.
     */
    public function testCanFilterActiveAppsByLevel()
    {
        $app_info_extra = array(
          'paddle' => array(
            'level' => App::LEVEL_EXTRA,
          ),
        );

        $apps = array();

        $apps[] = new App(array('name' => 'free 1'));
        $apps[] = new App(array('name' => 'free 2'));

        $apps[] = new App($app_info_extra + array('name' => 'extra 1'));
        $apps[] = new App($app_info_extra + array('name' => 'extra 2'));
        $apps[] = new App($app_info_extra + array('name' => 'extra 3'));
        $apps[] = new App($app_info_extra + array('name' => 'extra 4'));

        $repository = new FixedAppRepository($apps);

        $subscriptionPolicy = $this->getMock('Drupal\\paddle_subscription\\Subscription\\Policy');

        $appStore = new AppStore($subscriptionPolicy, $repository);

        $free_apps = $appStore->activeAppsByLevel(App::LEVEL_FREE);
        $this->assertCount(2, $free_apps);

        $extra_apps = $appStore->activeAppsByLevel(App::LEVEL_EXTRA);
        $this->assertCount(4, $extra_apps);

        $this->assertEquals(
            2,
            $appStore->countActiveAppsByLevel(App::LEVEL_FREE)
        );
        $this->assertEquals(
            4,
            $appStore->countActiveAppsByLevel(App::LEVEL_EXTRA)
        );
    }
}
