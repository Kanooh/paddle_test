<?php
/**
 * @file
 * paddle_product.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_product_ctools_plugin_api($module = NULL, $api = NULL) {
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
function paddle_product_node_info() {
  $items = array(
    'paddle_product' => array(
      'name' => t('Product'),
      'base' => 'node_content',
      'description' => t('Create a product with specific markup and maybe a button to request a product online or to make an appointment.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
