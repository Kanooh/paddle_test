<?php
/**
 * @file
 * Definition of Drupal\paddle_apps\AppRepository.
 */

namespace Drupal\paddle_apps;

/**
 * Interface for app repositories.
 */
interface AppRepository {

  /**
   * Get all active apps.
   *
   * An app is considered to be active if it's either
   * enabled already or if there is a pending action to enable it.
   *
   * @return App[]
   *   A list of active apps.
   */
  public function getActiveApps();
}
