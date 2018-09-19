<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy\Kanooh\Level3.
 */

namespace Drupal\paddle_subscription\Subscription\Policy\Kanooh;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppStore;
use Drupal\paddle_subscription\Subscription\Policy;
use Drupal\paddle_user\UserStore;

/**
 * Policy for kañooh's most expensive subscription type.
 */
class Level3 implements Policy {

  /**
   * {@inheritdoc}
   */
  public function humanReadableName() {
    return t('premium', array(), array('context' => 'subscription-policy'));
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
  public function canInstall(AppStore $store, App $app) {
    // Unlimited app installations.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function reachingLimit(AppStore $store) {
    // Unlimited app installations.
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function usersLeft(UserStore $store) {
    // Unlimited number of users.
    return -1;
  }

  /**
   * {@inheritdoc}
   */
  public function canAddAnotherUser(UserStore $store) {
    // Unlimited number of users.
    return TRUE;
  }

}
