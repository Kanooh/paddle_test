<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level1.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\Kanooh;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy;
use Drupal\paddle_user\UserStore;

/**
 * Policy for kañooh's cheapest subscription type.
 */
class Level1 implements Policy {

  /**
   * Upper limit on apps with level 'free'.
   */
  const LIMIT_FREE = 5;

  /**
   * Upper limit on users.
   */
  const USER_LIMIT = 3;

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
    return t('go', array(), array('context' => 'subscription-policy'));
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
    $limit = self::LIMIT_FREE;
    $installed = $store->countActiveAppsByLevel(App::LEVEL_FREE);

    if ($installed >= $limit - 2) {
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
