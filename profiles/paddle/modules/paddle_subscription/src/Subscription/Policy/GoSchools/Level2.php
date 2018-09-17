<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\GoSchools\Level2.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\GoSchools;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy;
use Drupal\paddle_user\UserStore;

/**
 * Policy for GO! schools more expensive subscription type.
 */
class Level2 implements Policy {

  /**
   * Upper limit on apps with level 'free'.
   */
  const LIMIT_FREE = 10;

  /**
   * Upper limit on users.
   */
  const USER_LIMIT = 8;

  /**
   * {@inheritdoc}
   */
  public function canInstall(AppStore $store, App $app) {
    if ($app->getLevel() == App::LEVEL_FREE) {
      return $store->countActiveAppsByLevel(App::LEVEL_FREE) < self::LIMIT_FREE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function humanReadableName() {
    return t('standard', array(), array('context' => 'subscription-policy'));
  }

  /**
   * {@inheritdoc}
   */
  public function freeAppsLeft(AppStore $store) {
    $free = self::LIMIT_FREE - $store->countActiveAppsByLevel(App::LEVEL_FREE);
    return $free < 0 ? 0 : $free;
  }

  /**
   * {@inheritdoc}
   */
  public function reachingLimit(AppStore $store) {
    if ($this->freeAppsLeft($store) < 4) {
      return TRUE;
    }
    else {
      return FALSE;
    }
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
