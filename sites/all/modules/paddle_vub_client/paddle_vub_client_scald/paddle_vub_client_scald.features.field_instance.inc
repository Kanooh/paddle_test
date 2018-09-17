<?php
/**
 * @file
 * paddle_vub_client_scald.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_vub_client_scald_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-news_item-field_published_image'.
  $field_instances['node-news_item-field_published_image'] = array(
    'bundle' => 'news_item',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'paddle_scald_atom_field',
        'settings' => array(),
        'type' => 'paddle_scald_atom_default',
        'weight' => 13,
      ),
      'diff_standard' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'full' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
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
      'news_detailed' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'news_extended' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'news_short' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'node_content_pane_full' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'node_content_pane_summary' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_published_image',
    'label' => 'Published Featured image',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'paddle_scald_atom_field',
      'settings' => array(
        'allowed_types' => array(
          0 => 'image',
        ),
      ),
      'type' => 'paddle_scald_atom_image',
      'weight' => 33,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_vub_server_sid'.
  $field_instances['scald_atom-image-field_vub_server_sid'] = array(
    'bundle' => 'image',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Automatically filled in when publishing content types linked to scald atoms to the VUB Server',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 15,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_vub_server_sid',
    'label' => 'VUB Server SID',
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
  t('Automatically filled in when publishing content types linked to scald atoms to the VUB Server');
  t('Published Featured image');
  t('VUB Server SID');

  return $field_instances;
}
