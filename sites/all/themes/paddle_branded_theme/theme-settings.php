<?php

/**
 * @file
 * Hook implementations for the Paddle theme branded.
 */

/**
 * Implements hook_paddle_themer_plugin_instances().
 */
function paddle_theme_branded_paddle_themer_plugin_instances() {
  require_once drupal_get_path('theme', 'paddle_theme') . '/theme-settings.php';
  $plugin_instances = paddle_theme_paddle_themer_plugin_instances();

  foreach ($plugin_instances as $machine_name => &$plugin_instance) {
    if ($plugin_instance['plugin'] == 'font' && !empty($plugin_instance['allowed_values']['font_family'])) {
      $plugin_instance['allowed_values']['font_family'] = array(
        '"FlandersArtSerif-Light", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Serif Light',
        '"FlandersArtSerif-Regular", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Serif Regular',
        '"FlandersArtSerif-Medium", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Serif Medium',
        '"FlandersArtSans-Light", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Sans Light',
        '"FlandersArtSans-Regular", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Sans Regular',
        '"FlandersArtSans-Medium", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Flanders Sans Medium',
        '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lato light',
        '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lato Regular',
        '"latobold", "Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lato Bold',
      );
      $plugin_instance['default_values']['font_family'] = '"latoregular", "Lucida Sans Unicode", "Lucida Grande", sans-serif';
    }
  }
  // Setting default font for header
  // title and subtitle.
  $plugin_instances['header_title_font']['default_values']['font_family'] = '"FlandersArtSerif-Medium", "Lucida Sans Unicode", "Lucida Grande", sans-serif';
  $plugin_instances['header_subtitle_font']['default_values']['font_family'] = '"FlandersArtSerif-Regular", "Lucida Sans Unicode", "Lucida Grande", sans-serif';
  $plugin_instances['landingpage_description_font']['default_values']['font_family'] = '"latolight", "Lucida Sans Unicode", "Lucida Grande", sans-serif';
  $plugin_instances['landingpage_pane_listing_teaser_title']['default_values']['font_family'] = '"latobold", "Lucida Sans Unicode", "Lucida Grande", sans-serif';

  return $plugin_instances;
}
