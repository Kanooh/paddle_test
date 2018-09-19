<?php
/**
 * @file
 * paddle_core.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function paddle_core_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'bypass honeypot protection'.
  $permissions['bypass honeypot protection'] = array(
    'name' => 'bypass honeypot protection',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'honeypot',
  );

  return $permissions;
}
