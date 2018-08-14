<?php
/**
 * @file
 * paddle_web_universe_theme.default_paddle_themer.inc
 */

/**
 * Implements hook_default_paddle_themer_themes().
 */
function paddle_web_universe_theme_default_paddle_themer_themes() {
  $export = array();

  $theme = new stdClass();
  $theme->api_version = 1;
  $theme->name = 'web_universe_theme';
  $theme->human_name = 'Web Universe Theme';
  $theme->theme = 'web_universe_theme';
  $theme->style = array();
  $theme->image_id = 0;
  $theme->changed = 1511875896;
  $export['web_universe_theme'] = $theme;

  return $export;
}
