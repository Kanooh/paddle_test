<?php
/**
 * @file
 * paddle_maps.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_maps_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "facetapi" && $api == "facetapi_defaults") {
    return array("version" => "1");
  }
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function paddle_maps_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_maps_node_info() {
  $items = array(
    'paddle_maps_page' => array(
      'name' => t('Maps Page'),
      'base' => 'node_content',
      'description' => t('This page type is used to display map search'),
      'has_title' => '1',
      'title_label' => t('Titel'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
