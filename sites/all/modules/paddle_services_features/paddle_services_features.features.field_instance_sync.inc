<?php
/**
 * @file
 * paddle_services_features.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_services_features_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-services-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-services-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['scald_atom-file-field_paddle_general_tags'] = array(
    'synced' => FALSE,
  );

  return $field_data;
}
