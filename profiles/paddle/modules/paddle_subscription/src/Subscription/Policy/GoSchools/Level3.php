<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level3.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\GoSchools;

use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3 as KanoohLevel3;
use Drupal\paddle_user\UserStore;

/**
 * Policy for GO! schools most expensive subscription type.
 */
class Level3 extends KanoohLevel3 {

  /**
   * Upper limit on users.
   */
  const USER_LIMIT = 15;

  /**
   * {@inheritdoc}
   */
  public function usersLeft(UserStore $store) {
    return self::USER_LIMIT - $store->countActiveUsers();
  }

  /**
   * {@inheritdoc}
   */
  public function canAddAnotherUser(UserStore $store) {
    return $this->usersLeft($store) > 0;
  }

}
