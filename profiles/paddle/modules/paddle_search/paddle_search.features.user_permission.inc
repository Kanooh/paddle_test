<?php
/**
 * @file
 * paddle_search.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function paddle_search_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'use search_api_autocomplete for search_api_page_search'.
  $permissions['use search_api_autocomplete for search_api_page_search'] = array(
    'name' => 'use search_api_autocomplete for search_api_page_search',
    'roles' => array(
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'search_api_autocomplete',
  );

  return $permissions;
}
