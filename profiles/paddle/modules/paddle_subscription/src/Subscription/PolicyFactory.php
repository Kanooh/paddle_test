<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\PolicyFactory.
 */

namespace Drupal\paddle_subscription\Subscription;

use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1 as KanoohLevel1;
use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level2 as KanoohLevel2;
use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3 as KanoohLevel3;
use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level1 as GoSchoolsLevel1;
use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level2 as GoSchoolsLevel2;
use Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level3 as GoSchoolsLevel3;

/**
 * Factory for subscription policies.
 */
class PolicyFactory {

  /**
   * Creates a subscription policy based on a subscription type.
   *
   * @param string $subscription_type
   *   The type of subscription e.g. 'instap', 'standaard' or 'pro'.
   *   A list of possible values can be found in the Subscription class.
   *
   * @see Subscription
   *
   * @return Policy
   *   The subscription policy.
   *
   * @throws PolicyNotFoundException
   *   If a policy can not be found.
   */
  static public function createAppPolicy($subscription_type) {
    switch ($subscription_type) {
      case Subscription::LEVEL_ENTRY:
      case Subscription::KANOOH_LEVEL1:
        return new KanoohLevel1();

      case Subscription::LEVEL_STANDARD:
      case Subscription::KANOOH_LEVEL2:
        return new KanoohLevel2();

      case Subscription::LEVEL_PRO:
      case Subscription::KANOOH_LEVEL3:
        return new KanoohLevel3();

      case Subscription::GOSCHOOLS_LEVEL1:
        return new GoSchoolsLevel1();

      case Subscription::GOSCHOOLS_LEVEL2:
        return new GoSchoolsLevel2();

      case Subscription::GOSCHOOLS_LEVEL3:
        return new GoSchoolsLevel3();

      default:
        throw new PolicyNotFoundException(
          "Could not find a policy corresponding to the specified subscription type '{$subscription_type}'."
        );
    }
  }

}
