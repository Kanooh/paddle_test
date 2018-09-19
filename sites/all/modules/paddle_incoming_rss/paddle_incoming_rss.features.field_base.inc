<?php
/**
 * @file
 * paddle_incoming_rss.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function paddle_incoming_rss_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_feed_item_description'.
  $field_bases['field_feed_item_description'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_feed_item_description',
    'indexes' => array(
      'format' => array(
        0 => 'format',
      ),
    ),
    'locked' => 1,
    'module' => 'text',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'text_long',
  );

  return $field_bases;
}
