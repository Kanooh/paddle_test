<?php

/**
 * @file
 * Definition of Drupal\paddle_user\UserStore.
 */

namespace Drupal\paddle_user;

use Drupal\paddle_subscription\Subscription\Policy;

/**
 * An app store.
 *
 * Brings together concepts of a subscription policy and an app repository.
 */
class UserStore {

  /**
   * The subscription policy of the app store.
   *
   * @var Policy
   */
  protected $subscriptionPolicy;

  /**
   * Constructs a new user store.
   *
   * @param Policy $subscription_policy
   *   The subscription policy applicable to the app store.
   */
  public function __construct(Policy $subscription_policy) {

    $this->subscriptionPolicy = $subscription_policy;
  }

  /**
   * Count active users.
   *
   * @return int
   *   The amount of users that count as active users.
   */
  public function countActiveUsers() {
    $paddle_roles = paddle_user_paddle_user_roles();

    $query = db_select('users', 'u');
    $query->addField('u', 'uid');
    $query->innerJoin('users_roles', 'ur', 'u.uid = ur.uid');
    $query->innerJoin('role', 'r', 'r.rid = ur.rid');
    $query->condition('r.name', $paddle_roles, 'IN');
    $query->condition("u.status", 0, "<>");
    $query->groupBy("u.uid");
    $num_active_users = $query->execute()->rowCount();

    return $num_active_users;
  }

  /**
   * Checks if another user can be added.
   *
   * @return bool
   *   True if another user can be installed, false if not.
   */
  public function canAddAnotherUser() {
    return $this->subscriptionPolicy->canAddAnotherUser($this);
  }

  /**
   * Get the number of users we can still create.
   *
   * @return int
   *   The number of users we can create.
   */
  public function usersLeft() {
    return $this->subscriptionPolicy->usersLeft($this);
  }

  /**
   * Get the human readable name of the subscription policy.
   *
   * @return string
   *   The human readable name of the subscription policy.
   */
  public function getHumanReadableName() {
    return $this->subscriptionPolicy->humanReadableName();
  }

}
