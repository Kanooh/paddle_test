<?php
/**
 * @file
 * paddle_landing_page.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_landing_page_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }

  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }

  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }

  if ($module == 'panels' && $api == 'pipelines') {
    return array(
      'version' => 1,
      'path' => drupal_get_path('module', 'paddle_landing_page') . '/includes',
    );
  }
}

/**
 * Implements hook_node_info().
 */
function paddle_landing_page_node_info() {
  $items = array(
    'landing_page' => array(
      'name' => t('Landing Page'),
      'base' => 'node_content',
      'description' => t('This page type is used to add structured content. Generally it\'s a landing page within the website to create overviews and to navigate.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
