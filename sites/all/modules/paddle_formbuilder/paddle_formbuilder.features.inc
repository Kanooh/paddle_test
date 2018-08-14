<?php
/**
 * @file
 * paddle_formbuilder.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_formbuilder_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_formbuilder_node_info() {
  $items = array(
    'paddle_formbuilder_page' => array(
      'name' => t('Formbuilder'),
      'base' => 'node_content',
      'description' => t('This content type is used to create custom forms.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
