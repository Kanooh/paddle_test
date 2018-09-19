<?php
/**
 * @file
 * paddle_poll.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_poll_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-poll-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_poll_question'] = array(
    'synced' => FALSE,
  );
  $field_data['node-poll-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-poll-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );

  return $field_data;
}
