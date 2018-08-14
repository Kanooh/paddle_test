<?php
/**
 * @file
 * paddle_social_identities.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_social_identities_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_social_identity_urls'
  $field_bases['field_social_identity_urls'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_social_identity_urls',
    'foreign keys' => array(),
    'indexes' => array(),
    'locked' => 0,
    'module' => 'url',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'url',
  );

  return $field_bases;
}
