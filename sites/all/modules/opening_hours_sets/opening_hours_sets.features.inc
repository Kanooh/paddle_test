<?php
/**
 * @file
 * opening_hours_sets.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function opening_hours_sets_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function opening_hours_sets_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}
