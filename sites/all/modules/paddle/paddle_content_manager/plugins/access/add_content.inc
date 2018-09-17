<?php
/**
 * @file
 * Plugin to check whether the user can add content or not.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Content Manager: User can add content.'),
  'description' => t('Checks that the current user can add content.'),
  'callback' => 'paddle_content_manager_add_content_ctools_access_check',
  'settings form' => 'paddle_content_manager_add_content_ctools_settings',
  'summary' => 'paddle_content_manager_add_content_ctools_summary',
);

/**
 * Check for access.
 */
function paddle_content_manager_add_content_ctools_access_check($conf, $context) {
  $content_types = array_keys(node_type_get_types());
  foreach ($content_types as $content_type) {
    if (user_access('create ' . $content_type . ' content')) {
      return TRUE;
    }
  }
  return FALSE;
}

/**
 * Settings form.
 */
function paddle_content_manager_add_content_ctools_settings(&$form, &$form_state, $conf) {
  return $form;
}

/**
 * Summary description.
 */
function paddle_content_manager_add_content_ctools_summary($conf, $context) {
  return t('Current user can create content.');
}
