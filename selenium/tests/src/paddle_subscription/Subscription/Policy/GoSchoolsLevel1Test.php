<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\GoSchoolsLevel1Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level1 as GoSchoolsLevel1Policy;

/**
 * Tests the policy for the cheapest GO! schools subscription type.
 *
 * Currently identical to the cheapest kañooh subscription type.
 */
class GoSchoolsLevel1Test extends KanoohLevel1Test
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->policy = new GoSchoolsLevel1Policy();
    }
}
