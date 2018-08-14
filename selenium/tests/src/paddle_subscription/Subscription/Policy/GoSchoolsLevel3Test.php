<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\GoSchoolsLevel3Test.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level3;

/**
 * Tests the policy for the most expensive GO! schools subscription type.
 *
 * Currently identical to the most expensive kañooh subscription type.
 */
class GoSchoolsLevel3Test extends KanoohLevel3Test
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->policy = new Level3();
    }
}
