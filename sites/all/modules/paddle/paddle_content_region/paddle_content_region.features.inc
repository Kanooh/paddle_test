<?php
/**
 * @file
 * paddle_content_region.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_content_region_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == 'panels' && $api == 'pipelines') {
    return array(
      'version' => 1,
      'path' => drupal_get_path('module', 'paddle_content_region') . '/includes',
    );
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
