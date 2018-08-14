<?php
/**
 * @file
 * paddle_panels_renderer.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_panels_renderer_ctools_plugin_api() {
  list($module, $api) = func_get_args();

  if ($module == 'panels' && $api == 'pipelines') {
    return array(
      'version' => 1,
      'path' => drupal_get_path('module', 'paddle_panels_renderer') . '/includes',
    );
  }
}
