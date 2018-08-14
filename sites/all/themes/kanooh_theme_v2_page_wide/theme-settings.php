<?php

/**
 * @file
 * Hook implementations for the Page Wide theme.
 */

require_once drupal_get_path('theme', 'kanooh_theme_v2') . '/theme-settings.php';

/**
 * Implements hook_paddle_themer_style_set().
 */
function kanooh_theme_v2_page_wide_paddle_themer_style_set() {
  // Temporary style set for testing purposes.
  $style_sets = kanooh_theme_v2_paddle_themer_style_set();

  $style_sets['header']['sections']['header_positioning'] = array(
    'title' => t('Position logo and navigation'),
    'plugins' => array(
      'header_positioning' => '',
    ),
  );

  return $style_sets;
}

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function kanooh_theme_v2_page_wide_paddle_themer_plugin_instances() {
  $plugin_instances = kanooh_theme_v2_paddle_themer_plugin_instances();

  $plugin_instances['header_positioning'] = array(
    'plugin' => 'header_positioning',
  );

  return $plugin_instances;
}
