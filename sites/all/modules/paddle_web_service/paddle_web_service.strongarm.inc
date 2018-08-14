<?php
/**
 * @file
 * paddle_web_service.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_web_service_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'restful_enable_users_resource';
  $strongarm->value = 0;
  $export['restful_enable_users_resource'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'restful_enable_user_login_resource';
  $strongarm->value = 0;
  $export['restful_enable_user_login_resource'] = $strongarm;

  return $export;
}
