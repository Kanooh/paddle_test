<?php
/**
 * @file
 * paddle_custom_page_layout.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_custom_page_layout_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "gridbuilder" && $api == "default_gridbuilder_grids") {
    return array("version" => "1");
  }
  if ($module == "layout" && $api == "default_layout_breakpoint") {
    return array("version" => "1");
  }
  if ($module == "layout" && $api == "default_layout_region") {
    return array("version" => "1");
  }
  if (($module == 'panels' && $api == 'layouts')) {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
