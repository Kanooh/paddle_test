<?php
/**
 * @file
 * paddle_publication.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_publication_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_publication_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_publication_node_info() {
  $items = array(
    'paddle_publication' => array(
      'name' => t('Publication'),
      'base' => 'node_content',
      'description' => t('This page is used to communicate publications on your website.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
