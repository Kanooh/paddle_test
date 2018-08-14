<?php
/**
 * @file
 * paddle_company_guide.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_company_guide_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_company_guide_node_info() {
  $items = array(
    'company_page' => array(
      'name' => t('Company page'),
      'base' => 'node_content',
      'description' => t('This page is used to display information from companies gathered in the company guide module.'),
      'has_title' => '1',
      'title_label' => t('Company name'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
