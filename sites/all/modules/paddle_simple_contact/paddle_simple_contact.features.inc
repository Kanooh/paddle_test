<?php
/**
 * @file
 * paddle_simple_contact.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_simple_contact_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "page_manager" && $api == "pages_default") {
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
function paddle_simple_contact_node_info() {
  $items = array(
    'simple_contact_page' => array(
      'name' => t('Simple Contact Page'),
      'base' => 'node_content',
      'description' => t('Via this page the visitor can leave contact information and ask questions. A contact form is a full page that can be added as a pane afterwards.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
