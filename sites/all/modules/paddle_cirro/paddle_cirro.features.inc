<?php
/**
 * @file
 * paddle_cirro.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_cirro_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
    return array("version" => "1");
  }
  if ($module == "facetapi" && $api == "facetapi_defaults") {
    return array("version" => "1");
  }
  if ($module == "feeds" && $api == "feeds_importer_default") {
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
 * Implements hook_node_info().
 */
function paddle_cirro_node_info() {
  $items = array(
    'paddle_cirro_page' => array(
      'name' => t('CIRRO page'),
      'base' => 'node_content',
      'description' => t('This page contains all information of resources retrieved from the CIRRO API.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
