<?php
/**
 * @file
 * paddle_vub_client_tags.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_vub_client_tags_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'taxonomy_term-vub_tags-field_vub_server_tid'.
  $field_instances['taxonomy_term-vub_tags-field_vub_server_tid'] = array(
    'bundle' => 'vub_tags',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_vub_server_tid',
    'label' => 'VUB Server TID',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 31,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('VUB Server TID');

  return $field_instances;
}
