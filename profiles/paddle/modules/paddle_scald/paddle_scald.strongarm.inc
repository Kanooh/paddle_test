<?php
/**
 * @file
 * paddle_scald.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_scald_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'scald_atom_defaults';
  $strongarm->value = array(
    'file' => (object) array(
      'thumbnail_source' => 'public://atoms/thumbnail_default.png',
      'description' => '',
      'upload_type' => 'plupload',
      'actions' => 15,
    ),
    'image' => (object) array(
      'thumbnail_source' => 'public://atoms/image.png',
      'description' => '',
      'upload_type' => 'plupload',
      'actions' => 15,
    ),
    'video' => (object) array(
      'thumbnail_source' => 'public://atoms/video.png',
      'description' => '',
      'upload_type' => 'plupload',
      'actions' => 15,
    ),
  );
  $export['scald_atom_defaults'] = $strongarm;

  return $export;
}
