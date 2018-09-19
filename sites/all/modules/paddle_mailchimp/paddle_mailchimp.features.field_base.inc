<?php
/**
 * @file
 * paddle_mailchimp.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_mailchimp_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_paddle_mailchimp_cid'.
  $field_bases['field_paddle_mailchimp_cid'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_mailchimp_cid',
    'indexes' => array(
      'format' => array(
        0 => 'format',
      ),
    ),
    'locked' => 0,
    'module' => 'text',
    'settings' => array(
      'max_length' => 32,
    ),
    'translatable' => 0,
    'type' => 'text',
  );

  return $field_bases;
}
