<?php
/**
 * @file
 * paddle_vub_tags.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_vub_tags_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_vub_tags'.
  $field_bases['field_vub_tags'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_vub_tags',
    'indexes' => array(
      'tid' => array(
        0 => 'tid',
      ),
    ),
    'locked' => 0,
    'module' => 'taxonomy',
    'settings' => array(
      'allowed_values' => array(
        0 => array(
          'vocabulary' => 'vub_tags',
          'parent' => 0,
        ),
      ),
      'options_list_callback' => 'i18n_taxonomy_allowed_values',
    ),
    'translatable' => 0,
    'type' => 'taxonomy_term_reference',
  );

  return $field_bases;
}
