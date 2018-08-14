<?php

/**
 * @file
 * This file handles views api definitions.
 */

/**
 * Implements hook_views_data().
 */
function opening_hours_sets_views_data() {
  $data = array();

  $data['opening_hours_sets']['edit_link'] = array(
    'group' => t('Opening hours set'),
    'title' => t('Edit link'),
    'help' => t('A link to edit the opening hours set.'),
    'field' => array(
      'handler' => 'opening_hours_sets_handler_field_edit_link',
    ),
  );

  $data['opening_hours_sets']['delete_link'] = array(
    'group' => t('Opening hours set'),
    'title' => t('Delete link'),
    'help' => t('A link to delete the opening hours set.'),
    'field' => array(
      'handler' => 'opening_hours_sets_handler_field_delete_link',
    ),
  );

  return $data;
}
