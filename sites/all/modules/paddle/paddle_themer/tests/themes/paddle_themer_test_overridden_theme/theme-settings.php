<?php
/**
 * @file
 * Theme settings for Paddle Themer Test Theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function paddle_themer_test_overridden_theme_paddle_themer_style_set() {
  $style_sets = array();

  $style_sets['main'] = array(
    'title' => t('Main'),
    'selectors' => array(
      'body' => array(
        'title' => 'Overridden',
        'plugins' => array(
          array(
            'background' => t('Background'),
          ),
        ),
      ),
    ),
  );

  return $style_sets;
}

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function paddle_themer_test_overridden_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  $plugin_instances['background'] = array(
    'plugin' => 'background',
    'selector' => '.background',
    'allowed_values' => array(
      'background_image' => array(
        'min_resolution' => '1140x100',
        'max_resolution' => '1140x1140',
      ),
      'repeat' => TRUE,
      'attachment' => TRUE,
      'position' => TRUE,
    ),
  );

  return $plugin_instances;
}
