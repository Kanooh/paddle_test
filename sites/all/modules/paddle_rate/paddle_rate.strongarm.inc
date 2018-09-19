<?php
/**
 * @file
 * paddle_rate.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_rate_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'votingapi_source_callback';
  $strongarm->value = 'session_api_get_sid';
  $export['votingapi_source_callback'] = $strongarm;

  return $export;
}
