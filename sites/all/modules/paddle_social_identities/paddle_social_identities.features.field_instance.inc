<?php
/**
 * @file
 * paddle_social_identities.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_social_identities_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'paddle_social_identity-paddle_social_identity-field_social_identity_urls'
  $field_instances['paddle_social_identity-paddle_social_identity-field_social_identity_urls'] = array(
    'bundle' => 'paddle_social_identity',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'url',
        'settings' => array(
          'nofollow' => FALSE,
          'trim_length' => 80,
        ),
        'type' => 'url_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paddle_social_identity',
    'field_name' => 'field_social_identity_urls',
    'label' => 'Social identity URLs',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'title_fetch' => 0,
      'title_field' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'url',
      'settings' => array(
        'size' => 255,
      ),
      'type' => 'url_external',
      'weight' => 1,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Social identity URLs');

  return $field_instances;
}
