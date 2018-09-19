<?php
/**
 * @file
 * paddle_simple_contact.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_simple_contact_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-simple_contact_page-field_paddle_contact_form'
  $field_instances['node-simple_contact_page-field_paddle_contact_form'] = array(
    'bundle' => 'simple_contact_page',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'paddle_simple_contact_field',
        'settings' => array(),
        'type' => 'simple_contact_form_default',
        'weight' => 2,
      ),
      'diff_standard' => array(
        'label' => 'hidden',
        'module' => 'paddle_simple_contact_field',
        'settings' => array(),
        'type' => 'simple_contact_form_default',
        'weight' => 2,
      ),
      'listing_teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'listing_title' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'node_content_pane_full' => array(
        'label' => 'hidden',
        'module' => 'paddle_simple_contact_field',
        'settings' => array(),
        'type' => 'simple_contact_form_default',
        'weight' => 1,
      ),
      'node_content_pane_summary' => array(
        'label' => 'hidden',
        'module' => 'paddle_simple_contact_field',
        'settings' => array(),
        'type' => 'simple_contact_form_default',
        'weight' => 1,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_paddle_contact_form',
    'label' => 'Contact form',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'paddle_simple_contact_field',
      'settings' => array(),
      'type' => 'simple_contact_form_default',
      'weight' => 8,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Contact form');

  return $field_instances;
}
