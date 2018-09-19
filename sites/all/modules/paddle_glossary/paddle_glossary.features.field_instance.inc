<?php
/**
 * @file
 * paddle_glossary.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_glossary_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance:
  // 'paddle_glossary_definition-paddle_glossary_definition-field_glossary_description'
  $field_instances['paddle_glossary_definition-paddle_glossary_definition-field_glossary_description'] = array(
    'bundle' => 'paddle_glossary_definition',
    'default_value' => array(
      0 => array(
        'value' => '',
      ),
    ),
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
    'entity_type' => 'paddle_glossary_definition',
    'field_name' => 'field_glossary_description',
    'label' => 'Description',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 0,
          'glossary_full_html' => 'glossary_full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'glossary_full_html' => array(
              'weight' => -20,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 1,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Description');

  return $field_instances;
}
