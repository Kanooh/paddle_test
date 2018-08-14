<?php

/**
 * @file
 * Hook implementations for the VUB theme.
 */

/**
 * Implements hook_paddle_themer_style_set().
 */
function vub_theme_paddle_themer_style_set() {
  $style_sets = array();

  $style_sets['header'] = array(
    'title' => t('Header'),
    'sections' => array(
      'website_header' => array(
        'title' => '',
        'plugins' => array(
          'branding_favicon' => t('Upload favicon'),
          'header_title_text' => t('Header title'),
          'boxmodel_header_title' => '',
          'header_subtitle_text' => t('Header subtitle'),
          'boxmodel_header_subtitle' => '',
          'vub_logo' => t('VUB logo'),
          'branding_logo' => t('Research group logo'),
        ),
      ),
      'search_box' => array(
        'title' => t('Search'),
        'plugins' => array(
          'show_search_box' => '',
          'search_placeholder_popup_checkbox' => '',
          'search_box_options' => '',
        ),
      ),
      'header_positioning' => array(
        'title' => t('Position logo and navigation'),
        'plugins' => array(
          'header_positioning' => '',
        ),
      ),
    ),
  );

  $style_sets['body'] = array(
    'title' => t('Body'),
    'sections' => array(
      'panes' => array(
        'title' => t('Pane settings'),
        'plugins' => array(
          'display_pane_top_as_h2' => '',
        ),
      ),
      'color_palettes' => array(
        'title' => t('Colour scheme'),
        'plugins' => array(
          'color_palettes' => '',
        ),
      ),
    ),
    'weight' => 1,
  );

  // Get all types of our nodes and create the breadcrumb trail
  // and next level checkboxes for them.
  $types = node_type_get_types();
  $style_sets['body']['sections']['breadcrumbs_navigation']['title'] = t('Breadcrumb navigation');

  foreach ($types as $type) {
    $style_sets['body']['sections']['breadcrumbs_navigation']['plugins']['show_breadcrumbs_for_' . $type->type] = '';
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $style_sets['body']['sections']['breadcrumbs_navigation']['plugins']['show_breadcrumbs_for_other_pages'] = '';

  $style_sets['body']['sections']['show_level_below']['title'] = t('Show next level menu items');

  foreach ($types as $type) {
    $style_sets['body']['sections']['show_level_below']['plugins']['show_level_below_' . $type->type] = '';
  }

  return $style_sets;
}

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function vub_theme_paddle_themer_plugin_instances() {
  $plugin_instances = array();

  $plugin_instances['branding_favicon'] = array('plugin' => 'favicon');

  $plugin_instances['header_title_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_title' => TRUE),
  );

  $plugin_instances['boxmodel_header_title'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h1.header-title',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );

  $plugin_instances['header_subtitle_text'] = array(
    'plugin' => 'paddle_core_header',
    'allowed_values' => array('header_subtitle' => TRUE),
  );

  $plugin_instances['boxmodel_header_subtitle'] = array(
    'plugin' => 'boxmodel',
    'selector' => 'h2.header-subtitle',
    'allowed_values' => array('margin_top' => TRUE, 'margin_left' => TRUE),
  );

  $plugin_instances['branding_logo'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('logo' => TRUE),
  );

  $plugin_instances['vub_logo'] = array(
    'plugin' => 'paddle_core_branding',
    'allowed_values' => array('header_show_logo' => TRUE),
    'default_values' => array(
      'header_show_logo' => TRUE,
    ),
  );

  $plugin_instances['show_search_box'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show search box'),
    'default_values' => array(
      'show_search_box' => TRUE,
    ),
  );

  $plugin_instances['search_placeholder_popup_checkbox'] = array(
    'plugin' => 'checkbox',
    'label' => t('Use search box pop-up'),
    'default_values' => array(
      'search_placeholder_popup_checkbox' => TRUE,
    ),
  );


  $plugin_instances['search_box_options'] = array(
    'plugin' => 'paddle_core_search',
  );


  // Pane settings.
  $plugin_instances['display_pane_top_as_h2'] = array(
    'plugin' => 'checkbox',
    'label' => t("Make pane titles accessible following the WCAG guidelines. Take care: this setting can have a visual impact."),
    'default_values' => array(
      'display_pane_top_as_h2' => FALSE,
    ),
  );

  // Get all types of our nodes and create the next level checkbox for them.
  $types = node_type_get_types();

  foreach ($types as $type) {
    $plugin_instances['show_breadcrumbs_for_' . $type->type] = array(
      'plugin' => 'checkbox',
      'label' => t('Show the breadcrumb trail for a @type',
        array('@type' => $type->name)),
      'default_values' => array('show_breadcrumbs_for_' . $type->type => TRUE),
    );
  }

  $plugin_instances['show_breadcrumbs_for_other_pages'] = array(
    'plugin' => 'checkbox',
    'label' => t('Show the breadcrumb trail for pages which are not included in a page type category'),
    'default_values' => array('show_breadcrumbs_for_other_pages' => TRUE),
  );

  foreach ($types as $type) {
    $plugin_instances['show_level_below_' . $type->type] = array(
      'plugin' => 'checkbox',
      'label' => t('Show next level menu items on a @type',
        array('@type' => $type->name)),
      'default_values' => array('show_level_below_' . $type->type => TRUE),
    );
  }

  $plugin_instances['color_palettes'] = array(
    'plugin' => 'color_palettes',
    'allowed_values' => vub_theme_define_color_palettes(),
    'default_values' => array(
      'primary_color_palettes' => 'palette_vub',
    ),
  );

  $plugin_instances['header_positioning'] = array(
    'plugin' => 'header_positioning',
    'default_values' => array(
      'header_position' => 'customized',
      'position_fields' => array(
        'logo' => 'left',
        'navigation' => 'center',
        'sticky_header' => '1',
      ),
    ),
  );

  return $plugin_instances;
}

/**
 * Defines all color palettes to be used by the themer.
 *
 * @return array
 *   An associative array containing all color palettes.
 */
function vub_theme_define_color_palettes() {
  return array(
    'palette_vub' => array(
      'palette_vub' => array(
        'title' => 'VUB color scheme',
        'colors' => array(
          '#003399',
          '#FF6600',
          '#ffffff',
          '#2F3742',
          '#FF6600',
          '#ffffff',
          '#003399',
          '#3F4A59',
        ),
      ),
    ),
    'palette_vub_neutral' => array(
      'palette_vub_neutral' => array(
        'title' => 'Neutral VUB color scheme',
        'colors' => array(
          '#2F3742',
          '#3F4A59',
          '#ffffff',
          '#000000',
          '#3F4A59',
          '#ffffff',
          '#2F3742',
          '#3F4A59',
        ),
      ),
    ),
  );
}
