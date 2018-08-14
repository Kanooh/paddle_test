<?php
/**
 * @file
 * paddle_go_themes.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_go_themes_default_paddle_themer_themes() {
  $export = array();

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'go_theme';
  $theme->human_name = 'GO theme';
  $theme->theme = 'go_theme';
  $theme->style = array(
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'go_default',
    ),
    'branding_logo' => array(
      'header_show_logo' => 0,
      'logo' => 0,
    ),
    'boxmodel_logo' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_title_text' => array(
      'header_title' => '',
    ),
    'header_title_font' => array(
      'font_family' => 'Arial, Helvetica, sans-serif',
      'font_size' => '20px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'boxmodel_header_title' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_subtitle_text' => array(
      'header_subtitle' => '',
    ),
    'header_subtitle_font' => array(
      'font_family' => 'Arial, Helvetica, sans-serif',
      'font_size' => '20px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'boxmodel_header_subtitle' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_background' => array(
      'color_enabled' => 1,
      'background_color' => 'FFFFFF',
      'background_pattern' => 'no_image',
      'background_image' => 0,
      'background_position' => 'center center',
      'background_attachment' => 'scroll',
      'background_repeat' => 'no-repeat',
    ),
    'header_image' => array(
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'search_box_options' => array(
      'search_placeholder_text_checkbox' => 1,
      'search_placeholder_text' => 'Looking for what?',
      'search_placeholder_button_label_checkbox' => 1,
      'search_placeholder_button_label' => 'Search',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'fat_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 1,
      'background_color' => 'FFFFFF',
    ),
  );

  // Get all types of our nodes and create the next level
  // & breadcrumb checkbox for them.
  $show_breadcrumbs_for_settings = array();
  foreach (node_type_get_types() as $type) {
    $show_breadcrumbs_for_settings['show_breadcrumbs_for_' . $type->type] = array(
      'show_breadcrumbs_for_' . $type->type => 1,
    );
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $show_breadcrumbs_for_settings['show_breadcrumbs_for_other_pages'] = array(
    'show_breadcrumbs_for_other_pages' => 1,
  );

  $theme->style += $show_breadcrumbs_for_settings;
  $show_level_below_settings = array();
  foreach (node_type_get_types() as $type) {
    $show_level_below_settings['show_level_below_' . $type->type] = array(
      'show_level_below_' . $type->type => 0,
    );
  }
  $theme->style += $show_level_below_settings;

  $theme->image_id = 0;
  $theme->changed = 1368458871;
  $export['go_theme'] = $theme;

  return $export;
}
