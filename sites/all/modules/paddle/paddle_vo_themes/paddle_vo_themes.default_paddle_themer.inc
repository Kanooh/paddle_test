<?php
/**
 * @file
 * paddle_vo_themes.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_vo_themes_default_paddle_themer_themes() {
  $export = array();

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'vo_strict';
  $theme->human_name = 'VO Theme';
  $theme->theme = 'vo_theme';
  $theme->style = array(
    'branding_global_header' => array(
      'header_show_logo' => 0,
      'logo' => 0,
      'vo_branding' => 'vo_branding',
      'global_vo_tokens' => array(
        'header' => '',
        'footer' => '',
      ),
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_p_light',
    ),
    'branding_logo' => array(
      'header_show_logo' => 1,
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
      'footer_style' => 'thin_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 1,
      'background_color' => 'FFFFFF',
      'background_pattern' => 'no_image',
      'background_image' => 0,
      'background_position' => 'center center',
      'background_attachment' => 'scroll',
      'background_repeat' => 'no-repeat',
    ),
  );

  // Get all types of our nodes and create the next level checkbox for them.
  $show_breadcrumbs_for_settings = array();
  $show_level_below_settings = array();
  foreach (node_type_get_types() as $type) {
    $show_breadcrumbs_for_settings['show_breadcrumbs_for_' . $type->type] = array(
      'show_breadcrumbs_for_' . $type->type => 1,
    );
    $show_level_below_settings['show_level_below_' . $type->type] = array(
      'show_level_below_' . $type->type => 0,
    );
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $show_breadcrumbs_for_settings['show_breadcrumbs_for_other_pages'] = array(
    'show_breadcrumbs_for_other_pages' => 1,
  );

  $theme->style += $show_breadcrumbs_for_settings;
  $theme->style += $show_level_below_settings;

  $theme->image_id = 0;
  $theme->changed = 1437136594;
  $export['vo_strict'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'vo_standard';
  $theme->human_name = 'kañooh Theme';
  $theme->theme = 'paddle_theme_branded';
  $theme->style = array(
    'branding_global_header' => array(
      'vo_branding' => 'vo_branding',
      'global_vo_tokens' => array(
        'header' => '',
        'footer' => '',
      ),
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 1,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_a_light',
    ),
    'branding_logo' => array(
      'header_show_logo' => 1,
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
      'font_family' => '"FlandersArtSerif-Light", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => 'FFFFFF',
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
      'font_family' => '"FlandersArtSerif-Light", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => 'FFFFFF',
    ),
    'boxmodel_header_subtitle' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
      'background_position' => 'center center',
      'background_attachment' => 'scroll',
      'background_repeat' => 'repeat',
    ),
    'header_image' => array(
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'search_box_options' => array(
      'default_search_enabled' => 1,
      'default_search_text' => 'On this website',
      'google_custom_enabled' => 0,
      'google_custom_text' => 'On all websites',
    ),
    'body_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
      'background_position' => 'center center',
      'background_attachment' => 'scroll',
      'background_repeat' => 'no-repeat',
    ),
    'page_title_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'breadcrumb_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'landingpage_description_font' => array(
      'font_family' => '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'landingpage_pane_listing_teaser_title' => array(
      'font_family' => '"latobold", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'h2_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '30px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'h3_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
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
    'h4_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '18px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'h5_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'paragraph_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'link_bp_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'link_bp_font_visited' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'link_bp_font_hover' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'list_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'paragraph_pane_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'link_pane_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'list_pane_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'blockquote_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'fat_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'weave',
      'background_image' => 0,
      'background_position' => 'center center',
      'background_attachment' => 'scroll',
      'background_repeat' => 'repeat',
    ),
    'footer_level_1_menu_items_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => 'ededed',
    ),
    'footer_level_2_menu_items_font' => array(
      'font_family' => '"FlandersArtSerif-Light", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
    'disclaimer_link_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '15px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color_enabled' => 0,
      'font_color' => '',
    ),
  );

  $show_breadcrumbs_for_settings = array();
  $show_level_below_settings = array();
  foreach (node_type_get_types() as $type) {
    $show_breadcrumbs_for_settings['show_breadcrumbs_for_' . $type->type] = array(
      'show_breadcrumbs_for_' . $type->type => 1,
    );
    $show_level_below_settings['show_level_below_' . $type->type] = array(
      'show_level_below_' . $type->type => 1,
    );
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $show_breadcrumbs_for_settings['show_breadcrumbs_for_other_pages'] = array(
    'show_breadcrumbs_for_other_pages' => 1,
  );

  $theme->style += $show_breadcrumbs_for_settings;
  $theme->style += $show_level_below_settings;

  $theme->image_id = 0;
  $theme->changed = 1413206749;
  $export['vo_standard'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'vo_standard_non_branded';
  $theme->human_name = 'VO Standard (without branding)';
  $theme->theme = 'paddle_theme';
  $theme->style = array();
  $theme->image_id = 0;
  $theme->changed = 1368458871;
  $export['vo_standard_non_branded'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'kanooh_theme_v2';
  $theme->human_name = 'kañooh Theme 2.0';
  $theme->theme = 'kanooh_theme_v2';
  $theme->style = array(
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_a_light',
    ),
  );
  $theme->style += $show_level_below_settings;
  $theme->style += $show_breadcrumbs_for_settings;
  $theme->image_id = 0;
  $theme->changed = 1368458871;
  $export['kanooh_theme_v2'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'kanooh_theme_v2_page_wide';
  $theme->human_name = 'kañooh Theme 2.0 - Page wide';
  $theme->theme = 'kanooh_theme_v2_page_wide';
  $theme->style = array(
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_a_light',
    ),
  );
  $theme->style += $show_level_below_settings;
  $theme->style += $show_breadcrumbs_for_settings;
  $theme->image_id = 0;
  $theme->changed = 1368458872;
  $export['kanooh_theme_v2_page_wide'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'kanooh_theme_v2_vertical_navigation';
  $theme->human_name = 'kanooh theme 2.0 vertical navigation';
  $theme->theme = 'kanooh_theme_v2_vertical_navigation';
  $theme->style = array(
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_a_light',
    ),
  );
  $theme->style += $show_level_below_settings;
  $theme->style += $show_breadcrumbs_for_settings;
  $theme->image_id = 0;
  $theme->changed = 1368458872;
  $export['kanooh_theme_v2_vertical_navigation'] = $theme;

  return $export;
}
