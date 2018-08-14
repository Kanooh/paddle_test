<?php
/**
 * @file
 * paddle_news.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_news_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_paddle_news_image_position'
  $field_bases['field_paddle_news_image_position'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_news_image_position',
    'foreign keys' => array(),
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(
        'full_top' => 'full width top',
        'half_left' => 'floating left (50%)',
        'half_right' => 'floating right (50%)',
      ),
      'allowed_values_function' => '',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
