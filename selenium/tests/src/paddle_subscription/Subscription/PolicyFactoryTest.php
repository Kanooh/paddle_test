<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\PolicyFactoryTest
 */

namespace Drupal\Tests\paddle_subscription\Subscription;

use Drupal\paddle_subscription\Subscription\PolicyFactory;
use Drupal\paddle_subscription\Subscription\Subscription;

/**
 * Tests the Drupal\paddle_subscription\Subscription\PolicyFactory class.
 */
class PolicyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides valid subscription types and their corresponding classes.
     *
     * @return array
     *   The list of subscription types and their classes.
     * @see testCreatesAPolicyInstanceOfTheRightClass()
     */
    public function subscriptionTypes()
    {
        return array(
            array(
                Subscription::LEVEL_ENTRY,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1',
            ),
            array(
                Subscription::LEVEL_STANDARD,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level2',
            ),
            array(
                Subscription::LEVEL_PRO,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3',
            ),
            array(
                Subscription::KANOOH_LEVEL1,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1',
            ),
            array(
                Subscription::KANOOH_LEVEL2,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level2',
            ),
            array(
                Subscription::KANOOH_LEVEL3,
                'Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3',
            ),
            array(
                Subscription::GOSCHOOLS_LEVEL1,
                'Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level1',
            ),
            array(
                Subscription::GOSCHOOLS_LEVEL2,
                'Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level2',
            ),
            array(
                Subscription::GOSCHOOLS_LEVEL3,
                'Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level3',
            ),
        );
    }

    /**
     * Tests that createAppPolicy instantiates the correct policy class.
     *
     * @dataProvider subscriptionTypes
     * @param string $subscriptionType
     *   The subscription type.
     * @param string $expectedClass
     *   The expected class corresponding to the subscription type.
     */
    public function testCreatesAPolicyInstanceOfTheRightClass($subscriptionType, $expectedClass)
    {
        $policy = PolicyFactory::createAppPolicy($subscriptionType);

        $this->assertInstanceOf($expectedClass, $policy);
    }

    /**
     * Tests that providing an unknown subscription type is not allowed.
     *
     * @expectedException \Drupal\paddle_subscription\Subscription\PolicyNotFoundException
     * @expectedExceptionMessage Could not find a policy corresponding to the specified subscription type 'foo'.
     */
    public function testUnknownSubscriptionTypeIsNotAllowed()
    {
        PolicyFactory::createAppPolicy('foo');
    }
}
