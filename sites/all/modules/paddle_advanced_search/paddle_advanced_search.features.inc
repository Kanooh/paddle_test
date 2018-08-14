<?php
/**
 * @file
 * paddle_advanced_search.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_advanced_search_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_advanced_search_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_advanced_search_node_info() {
  $items = array(
    'paddle_advanced_search_page' => array(
      'name' => t('Advanced Search Page'),
      'base' => 'node_content',
      'description' => t('This page type is used to display advanced searches'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
