<?php

/**
 * @file
 * Hook implementations for the Web Universe theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function web_universe_theme_paddle_themer_style_set() {
  $style_sets = array();

  // 1. Branding.
  $style_sets['branding'] = array(
    'title' => t('Branding'),
    'plugins' => array(
      'color_scheme' => t('Color scheme'),
      // Options to turn on/off global header & footer + favicon upload.
      'branding_global_header' => t('Header and footer of the Flemish Government (VO)'),
    ),
    'weight' => 0,
  );

  $style_sets['header'] = array(
    'title' => t('Header'),
    'plugins' => array(
      'header_type' => t('Header type'),
      'header_title_text' => t('Header title'),
      'header_title_default' => '',
      'header_title_prefix_text' => t('Header title prefix'),
      'header_image' => '',
      'header_logo_tag_line' => '',
    ),
  );

  $style_sets['content'] = array(
    'title' => t('Content'),
    'sections' => array(
      'panes' => array(
        'title' => t('Pane settings'),
        'plugins' => array(
          'display_pane_top_as_h2' => '',
        ),
      ),
    ),
  );

  return $style_sets;
}

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function web_universe_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  // 1. Branding.
  $plugin_instances['color_scheme'] = array(
    'plugin' => 'paddle_web_universe_color_scheme',
    'allowed_values' => array('web_universe_color_scheme' => TRUE),
  );

  $plugin_instances['branding_global_header'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('global_vo_tokens' => TRUE),
    'default_values' => array('vo_branding' => 'vo_branding'),
  );

  $plugin_instances['header_type'] = array(
    'plugin' => 'paddle_web_universe_header',
    'allowed_values' => array('header_type' => TRUE),
  );

  $plugin_instances['header_title_text'] = array(
    'plugin' => 'paddle_web_universe_header',
    'allowed_values' => array('header_title' => TRUE),
  );

  $plugin_instances['header_title_default'] = array(
    'plugin' => 'checkbox',
    'label' => t('Use the site name as title'),
    'default_values' => array(
      'header_title_default' => FALSE,
    ),
  );

  $plugin_instances['header_title_prefix_text'] = array(
    'plugin' => 'paddle_web_universe_header',
    'allowed_values' => array('header_title_prefix' => TRUE),
  );

  $plugin_instances['header_image'] = array(
    'plugin' => 'paddle_web_universe_header',
    'allowed_values' => array('header_image' => TRUE),
  );

  $plugin_instances['header_logo_tag_line'] = array(
    'plugin' => 'paddle_web_universe_header',
    'allowed_values' => array('header_logo_tag_line' => TRUE),
  );

  // Pane settings.
  $plugin_instances['display_pane_top_as_h2'] = array(
    'plugin' => 'checkbox',
    'label' => t("Make pane titles accessible following the WCAG guidelines. Take care: this setting can have a visual impact."),
    'default_values' => array(
      'display_pane_top_as_h2' => TRUE,
    ),
  );

  return $plugin_instances;
}
