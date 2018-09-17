<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Policy.
 */

namespace Drupal\paddle_subscription\Subscription;

use Drupal\paddle_apps\AppStore;
use Drupal\paddle_apps\App;
use Drupal\paddle_user\UserStore;

/**
 * Interface for subscription policies.
 */
interface Policy {

  /**
   * Returns the human readable name of the policy.
   *
   * @return string
   *   Human readable name.
   */
  public function humanReadableName();

  /**
   * Checks if the policy allows to install the app, in a given app store.
   *
   * @param AppStore $store
   *   The app store.
   * @param App $app
   *   The app to check.
   *
   * @return bool
   *   True if the policy allows to install the app, false if not.
   */
  public function canInstall(AppStore $store, App $app);

  /**
   * Number of free apps left that the client can install.
   *
   * @param AppStore $store
   *   The app store.
   *
   * @return int
   *   Number of free apps left to install, or -1 if unlimited.
   */
  public function freeAppsLeft(AppStore $store);

  /**
   * Checks if the client is reaching the limit of apps that can be installed.
   *
   * @param AppStore $store
   *   The app store.
   *
   * @return bool
   *   True if the user is close to reaching the limit of installable apps.
   */
  public function reachingLimit(AppStore $store);

  /**
   * Check how many extra user accounts can be added.
   *
   * @param UserStore $store
   *   The app store.
   *
   * @return int
   *   Number of users left to install, or -1 if unlimited.
   */
  public function usersLeft(UserStore $store);

  /**
   * Checks if the policy allows adding another user.
   *
   * @param UserStore $store
   *   The app store.
   *
   * @return bool
   *   True if the policy allows to add another user, false if not.
   */
  public function canAddAnotherUser(UserStore $store);

}
