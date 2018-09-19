<?php
/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level1.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\GoSchools;

use Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1 as KanoohLevel1;
use Drupal\paddle_user\UserStore;

/**
 * Policy for GO! schools cheapest subscription type.
 */
class Level1 extends KanoohLevel1 {

  /**
   * Upper limit on users.
   */
  const USER_LIMIT = 3;

  /**
   * {@inheritdoc}
   */
  public function humanReadableName() {
    return t('base', array(), array('context' => 'subscription-policy'));
  }

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
