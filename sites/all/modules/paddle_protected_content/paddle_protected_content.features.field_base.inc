<?php
/**
 * @file
 * paddle_protected_content.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_protected_content_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_paddle_prot_pg_visibility'.
  $field_bases['field_paddle_prot_pg_visibility'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_prot_pg_visibility',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(
        'everyone' => 'Everyone',
        'authenticated' => 'Only logged in users, but all of them',
        'specific_roles' => 'Only specific logged in user roles',
      ),
      'allowed_values_function' => '',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  // Exported field_base: 'field_paddle_prot_pg_user_roles'.
  $field_bases['field_paddle_prot_pg_user_roles'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_prot_pg_user_roles',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(),
      'allowed_values_function' => 'paddle_protected_content_custom_user_roles',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
