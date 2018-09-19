<?php
/**
 * @file
 * paddle_simple_contact.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_simple_contact_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_paddle_contact_form'
  $field_bases['field_paddle_contact_form'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_contact_form',
    'foreign keys' => array(),
    'indexes' => array(),
    'locked' => 0,
    'module' => 'paddle_simple_contact_field',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'simple_contact_form',
  );

  return $field_bases;
}
