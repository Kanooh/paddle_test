<?php
/**
 * @file
 * Custom panel style container.
 */

/**
 * Implementation of hook_panels_styles().
 */
$plugin = array(
  'background_image' => array(
    'title' => t('Background image'),
    'description' => t('Background image'),
    'render region' => 'background_image_render_region',
    'settings form' => 'background_image_settings_form',
    'hook theme' => array(
      'background_image_theme_region' => array(
        'template' => 'background-image-region',
        'path' => drupal_get_path('module', 'paddle_page_wide_theme') . '/plugins/styles/background_image',
        'variables' => array(
          'content' => NULL,
        ),
      ),
    ),
  ),
);
