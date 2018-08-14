<?php
/**
 * @file
 * paddle_maps.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_maps_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_map_content_types'.
  $field_bases['field_map_content_types'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_map_content_types',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(),
      'allowed_values_function' => 'paddle_maps_get_content_types',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
