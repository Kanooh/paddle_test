<?php
/**
 * @file
 * Custom panel style page_wide.
 */

/**
 * Implementation of hook_panels_styles().
 */
$plugin = array(
  'page_wide' => array(
    'title' => t('Page wide (100%)'),
    'description' => t('Make this region fit the entire page width.'),
    'render region' => 'page_wide_render_region',
    'settings form' => 'background_image_settings_form',
    'hook theme' => array(
      'page_wide_theme_region' => array(
        'template' => 'page-wide-region',
        'path' => drupal_get_path('module', 'paddle_page_wide_theme') .
          '/plugins/styles/page_wide',
        'variables' => array(
          'content' => NULL,
        ),
      ),
    ),
  ),
);
