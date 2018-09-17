<?php
/**
 * @file
 * paddle_web_universe_theme.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_web_universe_theme_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "paddle_themer" && $api == "default_paddle_themer") {
    return array("version" => "1");
  }
}
