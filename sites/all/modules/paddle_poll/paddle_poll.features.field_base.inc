<?php
/**
 * @file
 * paddle_poll.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_poll_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_paddle_poll_chart_type'
  $field_bases['field_paddle_poll_chart_type'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_poll_chart_type',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(),
      'allowed_values_function' => 'paddle_poll_get_chart_types',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  // Exported field_base: 'field_paddle_poll_question'
  $field_bases['field_paddle_poll_question'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_paddle_poll_question',
    'indexes' => array(
      'format' => array(
        0 => 'format',
      ),
    ),
    'locked' => 0,
    'module' => 'text',
    'settings' => array(
      'max_length' => 255,
    ),
    'translatable' => 0,
    'type' => 'text',
  );

  return $field_bases;
}
