<?php
/**
 * @file
 * paddle_services_features.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_services_features_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function paddle_services_features_node_info() {
  $items = array(
    'services' => array(
      'name' => t('Services'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
