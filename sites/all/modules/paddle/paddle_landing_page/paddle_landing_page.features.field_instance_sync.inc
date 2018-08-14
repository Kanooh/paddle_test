<?php
/**
 * @file
 * paddle_landing_page.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_landing_page_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-landing_page-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_show_body'] = array(
    'synced' => FALSE,
  );
  $field_data['node-landing_page-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-landing_page-field_show_title'] = array(
    'synced' => FALSE,
  );

  return $field_data;
}
