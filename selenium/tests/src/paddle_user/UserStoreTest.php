<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_user\UserStoreTest.
 */

namespace Drupal\Tests\paddle_user;

use Drupal\paddle_user\UserStore;

class UserStoreTest extends \PHPUnit_Framework_TestCase
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
     * Tests that the user store verifies the subscription policy.
     *
     * @dataProvider subscriptionPolicyResults
     * @param bool $subscriptionPolicyResult
     *   The return value of the subscription policy.
     */
    public function testVerifiesSubscriptionPolicy($subscriptionPolicyResult)
    {
        $subscriptionPolicy = $this->getMock('Drupal\\paddle_subscription\\Subscription\\Policy');
        $subscriptionPolicy
          ->expects($this->once())
          ->method('canAddAnotherUser')
          ->will($this->returnValue($subscriptionPolicyResult));

        $userStore = new UserStore($subscriptionPolicy);

        $can_add_another_user = $userStore->canAddAnotherUser();

        $this->assertEquals($subscriptionPolicyResult, $can_add_another_user);
    }
}
