<?php
/**
 * @file
 * Paddle Themer preview API functions
 */

/**
 * Retrieves the currently active preview theme.
 *
 * The preview ID is retrieved from the URL. Preview IDs currently map
 * one-on-one with Paddle themes, but the preview system might be extended in
 * the future to support previewing of theme drafts.
 *
 * @return string
 *   The preview ID.
 */
function paddle_themer_preview_theme() {
  $theme = &drupal_static(__FUNCTION__);

  if (!$theme) {
    $query = drupal_get_query_parameters();
    if (isset($query['preview_theme'])) {
      $preview_id = $query['preview_theme'];

      $theme = paddle_themer_load($preview_id);
    }
  }

  return $theme;
}

/**
 * Constructs a url to preview a given theme on a given non-admin path.
 *
 * @param object $theme
 *   Theme to use for previewing.
 * @param string $path
 *   The drupal non-admin path to link to. If not set, the current path
 *   including all query parameters will be used.
 *
 * @return string
 *   The url to preview.
 */
function paddle_themer_preview_url($theme, $path = NULL) {
  if (!$path) {
    $path = $_GET['q'];
    $options['query'] = drupal_get_query_parameters();
  }
  $options['paddle_themer_disable_url_outbound_alter'] = TRUE;
  if ($theme && isset($theme->name)) {
    $options['query']['preview_theme'] = $theme->name;
  }

  $url = url($path, $options);

  return $url;
}

/**
 * Adds the paddle preview id to outbound URLs.
 *
 * @param string $path
 *   The path to alter.
 * @param array $options
 *   The options to alter.
 * @param object $preview_theme
 *   Theme to use for previewing.
 *
 * @see paddle_themer_outbound_url_alter()
 */
function paddle_themer_preview_outbound_url_add_id(&$path, &$options, $preview_theme) {
  $options += array(
    'paddle_themer_disable_url_outbound_alter' => FALSE,
  );

  if (!$options['external'] && !$options['paddle_themer_disable_url_outbound_alter']) {
    $options['query']['preview_theme'] = $preview_theme->name;
  }
}

/**
 * Gets a list of Paddle Themer themes that can be previewed.
 *
 * The currently active preview theme is excluded.
 *
 * The themes are ordered so the currently active preview theme would be the
 * first and/or the last element in the circle if it was not excluded.
 *
 * @param object $current_preview_theme
 *   Current theme used for previewing.
 *
 * @return array
 *   An array of theme objects.
 */
function paddle_themer_preview_themes($current_preview_theme) {
  ctools_include('export');

  $themes = paddle_themer_available_themes();

  // Sort themes by human-readable name.
  uasort($themes, function($a, $b) {
    return strcmp($a->human_name, $b->human_name);
  });

  $names = array_keys($themes);
  $current_key = array_search($current_preview_theme->name, $names);

  // Get themes starting from the current preview theme.
  $preview_themes = array_splice($themes, $current_key);

  // Append themes that were before the current preview theme.
  $preview_themes += $themes;

  // Remove current preview theme.
  array_shift($preview_themes);

  return $preview_themes;
}
