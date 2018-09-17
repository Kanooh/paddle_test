<?php
/**
 * @file
 * paddle_i18n.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_i18n_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
