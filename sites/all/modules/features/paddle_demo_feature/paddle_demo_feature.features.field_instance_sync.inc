<?php
/**
 * @file
 * paddle_demo_feature.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_demo_feature_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-demo-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-demo-field_paddle_demo_image'] = array(
    'synced' => FALSE,
  );
  $field_data['node-demo-field_paddle_demo_info_block_1'] = array(
    'synced' => FALSE,
  );
  $field_data['node-demo-field_paddle_demo_info_block_2'] = array(
    'synced' => FALSE,
  );
  $field_data['node-demo-field_paddle_demo_info_block_3'] = array(
    'synced' => FALSE,
  );

  return $field_data;
}
