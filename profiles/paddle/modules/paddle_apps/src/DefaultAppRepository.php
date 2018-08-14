<?php
/**
 * @file
 * Definition of Drupal\paddle_apps\DefaultAppRepository.
 */

namespace Drupal\paddle_apps;

/**
 * Default implementation of an app repository. Uses paddle_apps_active_apps().
 */
class DefaultAppRepository implements AppRepository {

  /**
   * {@inheritdoc}
   */
  public function getActiveApps() {
    return paddle_apps_active_apps();
  }
}
