<?php
/**
 * @file
 * paddle_faceted_search.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_faceted_search_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "facetapi" && $api == "facetapi_defaults") {
    return array("version" => "1");
  }
}
