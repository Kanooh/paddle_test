<?php
/**
 * @file
 * paddle_poll.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_poll_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
