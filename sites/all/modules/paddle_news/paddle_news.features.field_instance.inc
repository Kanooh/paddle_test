<?php
/**
 * @file
 * paddle_news.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_news_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-news_item-field_paddle_news_image_position'
  $field_instances['node-news_item-field_paddle_news_image_position'] = array(
    'bundle' => 'news_item',
    'default_value' => array(
      0 => array(
        'value' => 'half_left',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 10,
      ),
      'diff_standard' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 1,
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
      'news_extended' => array(
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
    'field_name' => 'field_paddle_news_image_position',
    'label' => 'Featured image position',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => FALSE,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_buttons',
      'weight' => 13,
    ),
  );

  // Exported field_instance: 'node-news_item-field_paddle_featured_image'.
  $field_instances['node-news_item-field_paddle_featured_image'] = array(
      'bundle' => 'basic_page',
      'deleted' => 0,
      'description' => '',
      'display' => array(
          'default' => array(
              'label' => 'above',
              'module' => 'paddle_scald_atom_field',
              'settings' => array(),
              'type' => 'paddle_scald_atom_default',
              'weight' => 9,
          ),
          'diff_standard' => array(
              'label' => 'above',
              'settings' => array(),
              'type' => 'paddle_news_featured_image',
              'weight' => 1,
          ),
          'full' => array(
              'label' => 'hidden',
              'settings' => array(),
              'type' => 'paddle_news_featured_image',
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
      'field_name' => 'field_paddle_featured_image',
      'label' => 'Featured image',
      'required' => 0,
      'settings' => array(
          'field_instance_sync' => 1,
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
          'weight' => 1,
      ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Featured image position');

  return $field_instances;
}
