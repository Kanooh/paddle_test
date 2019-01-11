<?php
/**
 * @file
 * paddle_teams.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_teams_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_teams_node_info() {
  $items = array(
    'teams' => array(
      'name' => t('Teams'),
      'base' => 'node_content',
      'description' => t('IT teams'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}