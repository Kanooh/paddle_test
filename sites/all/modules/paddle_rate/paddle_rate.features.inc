<?php
/**
 * @file
 * paddle_rate.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_rate_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
