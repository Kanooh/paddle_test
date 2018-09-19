<?php
/**
 * @file
 * paddle_vo_themes.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_vo_themes_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "paddle_themer" && $api == "default_paddle_themer") {
    return array("version" => "1");
  }
}
