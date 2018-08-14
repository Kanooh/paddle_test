<?php
/**
 * @file
 * paddle_varnish.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_varnish_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
