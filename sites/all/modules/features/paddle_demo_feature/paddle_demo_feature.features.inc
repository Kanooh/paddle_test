<?php
/**
 * @file
 * paddle_demo_feature.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_demo_feature_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_demo_feature_node_info() {
  $items = array(
    'demo' => array(
      'name' => t('Demo'),
      'base' => 'node_content',
      'description' => t('This is a demo content type for paddle test.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
