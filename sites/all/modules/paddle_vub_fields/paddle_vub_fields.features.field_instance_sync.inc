<?php
/**
 * @file
 * paddle_vub_fields.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_vub_fields_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-news_item-field_vub_force_publish'] = array(
    'synced' => FALSE,
  );

  return $field_data;
}
