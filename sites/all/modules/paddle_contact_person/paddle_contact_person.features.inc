<?php
/**
 * @file
 * paddle_contact_person.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_contact_person_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_contact_person_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function paddle_contact_person_node_info() {
  $items = array(
    'contact_person' => array(
      'name' => t('Contact person'),
      'base' => 'node_content',
      'description' => t('This page contains all relevant and professional information about a person. Generally it\'s used to link content to the right person via a corresponding pane.'),
      'has_title' => '1',
      'title_label' => t('Full name'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
