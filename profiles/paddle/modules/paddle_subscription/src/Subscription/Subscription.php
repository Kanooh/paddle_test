<?php

/**
 * @file
 * Definition of Drupal\paddle_subscription\Subscription\Subscription.
 */

namespace Drupal\paddle_subscription\Subscription;

/**
 * Container for Subscription related data.
 */
class Subscription {

  /**
   * Subscription level entry.
   *
   * @deprecated Use KANOOH_LEVEL1 instead.
   */
  const LEVEL_ENTRY = 'instap';

  /**
   * Subscription level standard.
   *
   * @deprecated Use KANOOH_LEVEL2 instead.
   */
  const LEVEL_STANDARD = 'standaard';

  /**
   * Subscription level pro.
   *
   * @deprecated Use KANOOH_LEVEL3 instead.
   */
  const LEVEL_PRO = 'pro';

  /**
   * Cheapest kañooh subscription level.
   */
  const KANOOH_LEVEL1 = 'kanooh_level1';

  /**
   * More expensive kañooh subscription level.
   */
  const KANOOH_LEVEL2 = 'kanooh_level2';

  /**
   * Most expensive kañooh subscription level.
   */
  const KANOOH_LEVEL3 = 'kanooh_level3';

  /**
   * Cheapest GO! schools subscription level.
   */
  const GOSCHOOLS_LEVEL1 = 'goschools_level1';

  /**
   * More expensive GO! schools subscription level.
   */
  const GOSCHOOLS_LEVEL2 = 'goschools_level2';

  /**
   * Most expensive GO! schools subscription level.
   */
  const GOSCHOOLS_LEVEL3 = 'goschools_level3';

  /**
   * Can't be instantiated, therefore we declare the constructor private.
   */
  protected function __construct() {}

}
