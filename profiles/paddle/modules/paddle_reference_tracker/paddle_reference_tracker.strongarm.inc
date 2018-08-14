<?php
/**
 * @file
 * paddle_reference_tracker.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_reference_tracker_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'reference_tracker_disabled_entity_types';
  $strongarm->value = array(
    0 => 'message_type_category',
    1 => 'message_type',
    2 => 'message',
  );
  $export['reference_tracker_disabled_entity_types'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'reference_tracker_workbench_moderation_track_revision';
  $strongarm->value = '2';
  $export['reference_tracker_workbench_moderation_track_revision'] = $strongarm;

  return $export;
}
