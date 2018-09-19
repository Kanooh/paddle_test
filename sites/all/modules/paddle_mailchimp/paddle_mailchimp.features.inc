<?php
/**
 * @file
 * paddle_mailchimp.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_mailchimp_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "elysia_cron" && $api == "default_elysia_cron_rules") {
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
 * Implements hook_views_api().
 */
function paddle_mailchimp_views_api($module = NULL, $api = NULL) {
  return array(
    "api" => "3.0",
    'template path' => drupal_get_path('module', 'paddle_mailchimp') . '/includes',
  );
}

/**
 * Implements hook_node_info().
 */
function paddle_mailchimp_node_info() {
  $items = array(
    'newsletter' => array(
      'name' => t('Newsletter'),
      'base' => 'node_content',
      'description' => t('This content type is used to create newsletters to be sent through MailChimp.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
