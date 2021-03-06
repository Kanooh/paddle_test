<?php
/**
 * @file
 * paddle_calendar.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_calendar_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-calendar_item-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_calendar_date'] = array(
    'synced' => FALSE,
  );
  $field_data['node-calendar_item-field_paddle_calendar_location'] = array(
    'synced' => FALSE,
  );
  $field_data['node-calendar_item-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-calendar_item-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );

  return $field_data;
}
