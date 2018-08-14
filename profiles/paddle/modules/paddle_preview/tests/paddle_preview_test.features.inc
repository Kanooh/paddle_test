<?php
/**
 * @file
 * paddle_preview_test.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_preview_test_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "paddle_themer" && $api == "default_paddle_themer") {
    return array("version" => "1");
  }
}
