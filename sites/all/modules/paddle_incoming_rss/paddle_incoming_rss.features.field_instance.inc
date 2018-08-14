<?php
/**
 * @file
 * paddle_incoming_rss.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_incoming_rss_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance:
  // 'paddle_incoming_rss_feed_item-paddle_incoming_rss_feed_item-field_feed_item_description'.
  $field_instances['paddle_incoming_rss_feed_item-paddle_incoming_rss_feed_item-field_feed_item_description'] = array(
    'bundle' => 'paddle_incoming_rss_feed_item',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
      'magazine' => array(
        'label' => 'hidden',
        'module' => 'smart_trim',
        'settings' => array(
          'more_link' => 0,
          'more_text' => 'Read more',
          'summary_handler' => 'full',
          'trim_length' => 300,
          'trim_link' => 0,
          'trim_options' => array(
            'text' => 'text',
          ),
          'trim_preserve_tags' => '<p> <br> <em> <strong>',
          'trim_suffix' => '...',
          'trim_type' => 'chars',
        ),
        'type' => 'smart_trim_format',
        'weight' => 2,
      ),
      'title' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paddle_incoming_rss_feed_item',
    'field_name' => 'field_feed_item_description',
    'label' => 'Description',
    'required' => FALSE,
    'settings' => array(
      'display_summary' => TRUE,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 0,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Description');

  return $field_instances;
}
