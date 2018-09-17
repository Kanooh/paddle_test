<?php
/**
 * @file
 * paddle_reference_tracker.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function paddle_reference_tracker_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access reference tracker information'.
  $permissions['access reference tracker information'] = array(
    'name' => 'access reference tracker information',
    'roles' => array(
      'Chief Editor' => 'Chief Editor',
      'Editor' => 'Editor',
      'Read Only' => 'Read Only',
      'Site Manager' => 'Site Manager',
    ),
    'module' => 'reference_tracker',
  );

  return $permissions;
}
