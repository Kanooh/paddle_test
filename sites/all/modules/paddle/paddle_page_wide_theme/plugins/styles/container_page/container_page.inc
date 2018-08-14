<?php
/**
 * @file
 * Custom panel style container.
 */

/**
 * Implementation of hook_panels_styles().
 */
$plugin = array(
  'container_page' => array(
    'title' => t('Container centered on page with a max width'),
    'description' => t('Container centered on page with a max width.'),
    'render region' => 'container_render_region',
    'settings form' => 'background_image_settings_form',
    'hook theme' => array(
      'container_theme_region' => array(
        'template' => 'container-region',
        'path' => drupal_get_path('module', 'paddle_page_wide_theme') . '/plugins/styles/container_page',
        'variables' => array(
          'content' => NULL,
        ),
      ),
    ),
  ),
);
