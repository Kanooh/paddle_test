<?php
/**
 * @file
 * Theme functions and template preprocess.
 */

/**
 * Preprocess variables for the newsletter template.
 */
function template_preprocess_paddle_mailchimp_html(&$variables) {
  global $theme;

  // Add the Zurb Ink css file.
  $variables['css_files'] = array();
  $variables['css_files'][] = drupal_get_path('module', 'paddle_mailchimp') . '/assets/ink.css';

  // If available, add the newsletter css styles from paddle_theme and the
  // current frontend theme.
  $paths = array();
  $paths[] = drupal_get_path('theme', 'paddle_theme');
  $paths[] = drupal_get_path('theme', variable_get('theme_default', $theme));
  $paths = array_unique($paths);
  foreach ($paths as $path) {
    $file_path = $path . '/css/newsletter.css';
    if (file_exists($file_path)) {
      $variables['css_files'][] = $file_path;
    }
  }

  // Provide common variables.
  $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  $variables['front_page'] = url();

  // Provide a link to the frontend view of the newsletter, if possible.
  $variables['archive_view'] = FALSE;
  if (!empty($variables['archive_url'])) {
    $variables['archive_view'] = t('If you can not read this newsletter, <a href="@url">click here</a> for an online version.', array(
      '@url' => $variables['archive_url'],
    ));
  }
}

/**
 * Process variables for the newsletter template.
 */
function template_process_paddle_mailchimp_html(&$variables) {
  // Read all csses added with preprocess functions to inline them.
  $variables['styles'] = array();
  foreach ($variables['css_files'] as $path) {
    $variables['styles'][] = drupal_load_stylesheet($path) . "\n";
  }
}
