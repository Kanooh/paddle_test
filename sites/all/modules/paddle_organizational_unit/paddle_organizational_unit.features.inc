<?php
/**
 * @file
 * paddle_organizational_unit.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_organizational_unit_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
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
function paddle_organizational_unit_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_organizational_unit_node_info() {
  $items = array(
    'organizational_unit' => array(
      'name' => t('Organizational unit'),
      'base' => 'node_content',
      'description' => t('This page contains all relevant and professional information about an organization. Generally it\'s used to link content of the right team via a corresponding pane.'),
      'has_title' => '1',
      'title_label' => t('Organization'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
