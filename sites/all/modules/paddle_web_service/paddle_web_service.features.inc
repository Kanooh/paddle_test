<?php
/**
 * @file
 * paddle_web_service.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_web_service_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
