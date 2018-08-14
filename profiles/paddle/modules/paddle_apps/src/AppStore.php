<?php
/**
 * @file
 * Definition of Drupal\paddle_apps\AppStore.
 */

namespace Drupal\paddle_apps;

use Drupal\paddle_subscription\Subscription\Policy;

/**
 * An app store.
 *
 * Brings together concepts of a subscription policy and an app repository.
 */
class AppStore {

  /**
   * The subscription policy of the app store.
   *
   * @var Policy
   */
  protected $subscriptionPolicy;

  /**
   * The app repository.
   *
   * @var AppRepository
   */
  protected $repository;

  /**
   * Constructs a new app store.
   *
   * @param Policy $subscription_policy
   *   The subscription policy applicable to the app store.
   */
  public function __construct(Policy $subscription_policy, AppRepository $repository) {

    $this->subscriptionPolicy = $subscription_policy;
    $this->repository = $repository;
  }

  /**
   * Checks if an app is installable, taking in account the subscription policy.
   *
   * @param App $app
   *   The app to check.
   *
   * @return bool
   *   True if the app can be installed, false if not.
   */
  public function canInstall(App $app) {
    return $this->subscriptionPolicy->canInstall($this, $app);
  }

  /**
   * Returns the string to notify the user how many paddlets he can install.
   *
   * @return string
   *   The string notifying the users how many more paddlets he can install.
   */
  public function reachingLimit() {
    return $this->subscriptionPolicy->reachingLimit($this);
  }

  /**
   * Get all active apps.
   *
   * @return App[]
   *   A list of App objects.
   */
  protected function getActiveApps() {
    return $this->repository->getActiveApps();
  }

  /**
   * Get all active apps, filtered by level.
   *
   * @param string $level
   *   The level to filter on.
   *
   * @return App[]
   *   A list of App objects.
   */
  public function activeAppsByLevel($level) {
    $active_apps = $this->getActiveApps();

    $filtered_apps = array_filter(
      $active_apps,
      // @codingStandardsIgnoreStart
      function (App $app) use ($level) {
        return $app->getLevel() == $level;
      }
    // @codingStandardsIgnoreEnd
    );

    return $filtered_apps;
  }

  /**
   * Get the amount of active apps, filtered by level.
   *
   * @param string $level
   *   The level to filter on.
   *
   * @return int
   *   The amount.
   */
  public function countActiveAppsByLevel($level) {
    return count($this->activeAppsByLevel($level));
  }
}
