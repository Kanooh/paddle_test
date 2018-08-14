<?php
/**
 * @file
 * paddle_panel_layouts.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_panel_layouts_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
