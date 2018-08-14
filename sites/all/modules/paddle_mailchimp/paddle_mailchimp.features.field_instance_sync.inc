<?php
/**
 * @file
 * paddle_mailchimp.features.field_instance_sync.inc
 */

/**
 * Implements hook_field_instance_sync_defaults().
 */
function paddle_mailchimp_field_instance_sync_defaults() {
  $field_data = array();

  $field_data['node-newsletter-body'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_paddle_featured_image'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_paddle_general_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_paddle_mailchimp_cid'] = array(
    'synced' => FALSE,
  );
  $field_data['node-newsletter-field_paddle_seo_description'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_paddle_seo_title'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_paddle_tags'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );
  $field_data['node-newsletter-field_page_responsible_author'] = array(
    'synced' => TRUE,
    'slave' => TRUE,
  );

  return $field_data;
}
