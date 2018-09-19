<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level2.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\Kanooh;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy;
use Drupal\paddle_user\UserStore;

/**
 * Policy for kañooh's more expensive subscription type.
 */
class Level2 implements Policy {

  /**
   * Upper limit on user.
   */
  const USER_LIMIT = 15;

  /**
   * {@inheritdoc}
   */
  public function canInstall(AppStore $store, App $app) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function humanReadableName() {
    return t('pro', array(), array('context' => 'subscription-policy'));
  }

  /**
   * {@inheritdoc}
   */
  public function freeAppsLeft(AppStore $store) {
    return -1;
  }

  /**
   * {@inheritdoc}
   */
  public function reachingLimit(AppStore $store) {
    return FALSE;
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
