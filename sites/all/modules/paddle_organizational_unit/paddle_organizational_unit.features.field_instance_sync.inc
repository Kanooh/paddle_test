<?php
/**
 * @file
 * paddle_organizational_unit.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_organizational_unit_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-organizational_unit-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_computed_ou'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_address'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_email'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_fax'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_head_unit'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_parent_1'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_parent_2'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_phone'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_ou_website'] = array(
    'synced' => FALSE,
  );
  $field_data['node-organizational_unit-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-organizational_unit-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );

  return $field_data;
}
