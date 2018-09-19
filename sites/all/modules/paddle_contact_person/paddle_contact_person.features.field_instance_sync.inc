<?php
/**
 * @file
 * paddle_contact_person.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_contact_person_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-contact_person-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_computed_cp'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_address'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_email'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_fax'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_first_name'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_function'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_last_name'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_linkedin'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_manager'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_mobile_office'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_ou_level_1'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_ou_level_2'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_ou_level_3'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_phone_office'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_skype'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_twitter'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_website'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_cp_yammer'] = array(
    'synced' => FALSE,
  );
  $field_data['node-contact_person-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_paddle_show_next_level'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-contact_person-field_show_breadcrumb'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );

  return $field_data;
}
