<?php
/**
 * @file
 * paddle_comment.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_comment_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance:
  // 'message-paddle_notifications_comment_added_responsible-field_paddle_notifications_node'
  $field_instances['message-paddle_notifications_comment_added_responsible-field_paddle_notifications_node'] = array(
    'bundle' => 'paddle_notifications_comment_added_responsible',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'entityreference',
        'settings' => array(
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 0,
      ),
      'message_notify_email_body' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'message_notify_email_subject' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'message',
    'field_name' => 'field_paddle_notifications_node',
    'label' => 'Node',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 2,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Node');

  return $field_instances;
}
