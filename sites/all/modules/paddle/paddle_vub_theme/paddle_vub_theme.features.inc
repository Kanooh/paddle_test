<?php
/**
 * @file
 * paddle_vub_theme.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_vub_theme_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "paddle_themer" && $api == "default_paddle_themer") {
    return array("version" => "1");
  }
}
