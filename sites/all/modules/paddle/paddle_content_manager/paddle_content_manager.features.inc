<?php
/**
 * @file
 * paddle_content_manager.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_content_manager_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == 'panels' && $api == 'pipelines') {
    return array(
      'version' => 1,
      'path' => drupal_get_path('module', 'paddle_content_manager') . '/includes',
    );
  }
  if ($module == "panels_mini" && $api == "panels_default") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function paddle_content_manager_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_content_manager_node_info() {
  $items = array(
    'basic_page' => array(
      'name' => t('Basic page'),
      'base' => 'node_content',
      'description' => t('This simple page is used to add unstructured content. Generally this is the lowest level in the website and is it used to publish detailed content.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
