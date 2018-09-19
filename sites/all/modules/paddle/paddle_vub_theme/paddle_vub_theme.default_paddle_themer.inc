<?php
/**
 * @file
 * paddle_vub_theme.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_vub_theme_default_paddle_themer_themes() {
  $export = array();

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

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'vub_theme';
  $theme->human_name = 'VUB Theme';
  $theme->theme = 'vub_theme';
  $theme->style = array(
    'color_palettes' => array(
      'primary_color_palettes' => 'palette_vub',
    ),
  );
  $theme->style += $show_level_below_settings;
  $theme->style += $show_breadcrumbs_for_settings;
  $theme->image_id = 0;
  $theme->changed = 1527498696;
  $export['vub_theme'] = $theme;

  return $export;
}
