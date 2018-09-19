<?php

/**
 * @file
 * This file handles views field handler definition.
 */

/**
 * Implements hook_views_data().
 */
function paddle_reference_tracker_views_data() {
  $data = array();

  $data['reference_tracker']['table']['group'] = t('Reference tracker');

  $data['reference_tracker']['table']['base'] = array(
    'title' => t('Reference tracker'),
    'help' => t('Contains reference tracker records'),
  );

  $data['reference_tracker']['table']['join'] = array(
    'node' => array(
      'left_field' => 'nid',
      'field' => 'entity_id',
    ),
    'reference_tracker_atoms' => array(
      'left_field' => 'sid',
      'field' => 'reference_entity_id',
    ),
  );

  $data['reference_tracker']['entity_id'] = array(
    'title' => t('Entity ID'),
    'real field' => 'entity_id',
    'help' => t('The entity ID of the node referencing this entity.'),
    'field' => array(
      'handler' => 'paddle_reference_tracker_handler_field_entity_id_link',
      'additional fields' => array('entity_type'),
    ),
  );

  $data['reference_tracker']['entity_type'] = array(
    'title' => t('Entity type'),
    'help' => t('The type of the entity referencing this entity.'),
    'field' => array(
      'handler' => 'paddle_reference_tracker_handler_field_entity_type',
    ),
  );

  $data['reference_tracker']['reference_entity_id'] = array(
    'title' => t('Reference entity ID'),
    'help' => t('The reference entity ID of the entity that is being referenced.'),
    'argument' => array(
      'handler' => 'views_handler_argument',
      'numeric' => TRUE,
      'validate type' => 'sid',
    ),
  );

  $data['reference_tracker']['reference_entity_type'] = array(
    'title' => t('Reference entity type'),
    'help' => t('The reference entity type of the entity that is being referenced.'),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
  );

  $data['reference_tracker']['entity_bundle'] = array(
    'title' => t('Entity bundle'),
    'real field' => 'entity_id',
    'help' => t('The bundle of the entity referencing this entity.'),
    'field' => array(
      'handler' => 'paddle_reference_tracker_handler_field_entity_bundle',
      'additional fields' => array('entity_type'),
    ),
  );

  return $data;
}
