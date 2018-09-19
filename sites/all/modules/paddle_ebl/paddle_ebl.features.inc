<?php
/**
 * @file
 * paddle_ebl.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_ebl_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
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
function paddle_ebl_node_info() {
  $items = array(
    'paddle_ebl_page' => array(
      'name' => t('EBL page'),
      'base' => 'node_content',
      'description' => t('This page is used to display publications from the EBL web service.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
