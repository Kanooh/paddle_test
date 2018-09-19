<?php
/**
 * @file
 * paddle_vo_additional_themes.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_vo_additional_themes_default_paddle_themer_themes() {
  $export = array();

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->image_path = drupal_get_path('module', 'paddle_vo_additional_themes') . '/thumbnails/red.png';
  $theme->name = '43c63090-fef7-4a17-90a7-6b63da5465ac';
  $theme->human_name = 'VO Red theme';
  $theme->theme = 'paddle_theme_branded';
  $theme->style = array(
    'branding_global_header' => array(
      'vo_branding' => 'vo_branding',
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_c',
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
      'header_title' => 'Vlaamse organisatie',
    ),
    'header_title_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'FFFFFF',
    ),
    'boxmodel_header_title' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_subtitle_text' => array(
      'header_subtitle' => 'voor een doelgroep',
    ),
    'header_subtitle_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
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
    ),
    'header_image' => array(
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'body_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'landingpage_description_font' => array(
      'font_family' => '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '30px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'thin_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'footer_level_1_menu_items_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'ededed',
    ),
  );
  $theme->image_id = 0;
  $theme->changed = 1386944212;
  $export['43c63090-fef7-4a17-90a7-6b63da5465ac'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->image_path = drupal_get_path('module', 'paddle_vo_additional_themes') . '/thumbnails/pink.png';
  $theme->name = '885c2f68-54a3-408c-947a-1557156a60a2';
  $theme->human_name = 'VO Pink Theme';
  $theme->theme = 'paddle_theme_branded';
  $theme->style = array(
    'branding_global_header' => array(
      'vo_branding' => 'vo_branding',
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_e',
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
      'header_title' => 'Vlaamse organisatie',
    ),
    'header_title_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'FFFFFF',
    ),
    'boxmodel_header_title' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_subtitle_text' => array(
      'header_subtitle' => 'voor een doelgroep',
    ),
    'header_subtitle_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
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
    ),
    'header_image' => array(
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'body_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'landingpage_description_font' => array(
      'font_family' => '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '30px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'thin_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'footer_level_1_menu_items_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'ededed',
    ),
  );
  $theme->image_id = 0;
  $theme->changed = 1386944336;
  $export['885c2f68-54a3-408c-947a-1557156a60a2'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->image_path = drupal_get_path('module', 'paddle_vo_additional_themes') . '/thumbnails/earthcolors.png';
  $theme->name = 'bbaca73c-2930-4fe2-b614-ca3d6792495b';
  $theme->human_name = 'VO Earth Colors';
  $theme->theme = 'paddle_theme_branded';
  $theme->style = array(
    'branding_global_header' => array(
      'vo_branding' => 'vo_branding',
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_d',
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
      'header_title' => 'Vlaamse organisatie',
    ),
    'header_title_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'FFFFFF',
    ),
    'boxmodel_header_title' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_subtitle_text' => array(
      'header_subtitle' => 'voor een doelgroep',
    ),
    'header_subtitle_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
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
    ),
    'header_image' => array(
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'body_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'landingpage_description_font' => array(
      'font_family' => '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '30px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'thin_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'footer_level_1_menu_items_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'ededed',
    ),
  );
  $theme->image_id = 0;
  $theme->changed = 1386944464;
  $export['bbaca73c-2930-4fe2-b614-ca3d6792495b'] = $theme;

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->image_path = drupal_get_path('module', 'paddle_vo_additional_themes') . '/thumbnails/yellow.png';
  $theme->name = 'f8b6079e-059e-4005-99b7-23c3c587b054';
  $theme->human_name = 'VO Yellow Theme';
  $theme->theme = 'paddle_theme_branded';
  $theme->style = array(
    'branding_global_header' => array(
      'vo_branding' => 'vo_branding',
    ),
    'branding_favicon' => array(
      'favicon' => 0,
      'toggle' => 0,
    ),
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_b',
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
      'header_title' => 'Vlaamse organisatie',
    ),
    'header_title_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '35px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'FFFFFF',
    ),
    'boxmodel_header_title' => array(
      'margin' => array(
        'margin_left' => '',
        'margin_top' => '',
      ),
    ),
    'header_subtitle_text' => array(
      'header_subtitle' => 'voor een doelgroep',
    ),
    'header_subtitle_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '25px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
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
    ),
    'header_image' => array(
      'background_image' => 0,
    ),
    'show_search_box' => array(
      'show_search_box' => 1,
    ),
    'body_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'landingpage_description_font' => array(
      'font_family' => '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '30px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
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
      'font_color' => '333333',
    ),
    'show_disclaimer' => array(
      'show_disclaimer' => 0,
    ),
    'footer' => array(
      'footer_style' => 'thin_footer',
    ),
    'footer_background' => array(
      'color_enabled' => 0,
      'background_color' => '',
      'background_pattern' => 'no_image',
      'background_image' => 0,
    ),
    'footer_level_1_menu_items_font' => array(
      'font_family' => '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif',
      'font_size' => '14px',
      'font_style' => array(
        'bold' => 0,
        'italic' => 0,
        'underline' => 0,
      ),
      'font_capitalization' => 'none',
      'font_color' => 'ededed',
    ),
  );
  $theme->image_id = 0;
  $theme->changed = 1386948409;
  $export['f8b6079e-059e-4005-99b7-23c3c587b054'] = $theme;

  // Get all types of our nodes and create the next level
  // & breadcrumb checkbox for them.
  $show_breadcrumbs_for_settings = array();
  $show_level_below_settings = array();
  foreach (node_type_get_types() as $type) {
    $show_breadcrumbs_for_settings['show_breadcrumbs_for_' . $type->type] = array(
      'show_breadcrumbs_for_' . $type->type => 0,
    );
    $show_level_below_settings['show_level_below_' . $type->type] = array(
      'show_level_below_' . $type->type => 1,
    );
  }

  // The breadcrumb can also be shown on pages which are not nodes.
  $show_breadcrumbs_for_settings['show_breadcrumbs_for_other_pages'] = array(
    'show_breadcrumbs_for_other_pages' => 1,
  );

  // Apply the show level below settings to all exported themes.
  foreach ($export as &$theme) {
    $theme->style += $show_breadcrumbs_for_settings;
    $theme->style += $show_level_below_settings;
  }

  return $export;
}
