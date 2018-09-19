<?php
/**
 * @file
 * General template processing functions.
 */

/**
 * Implements template_process_page().
 */
function paddle_admin_theme_process_page(&$variables) {

  // Checks how many columns are used.
  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['columns'] = 3;
  }
  elseif (!empty($variables['page']['sidebar_first'])) {
    $variables['columns'] = 2;
  }
  elseif (!empty($variables['page']['sidebar_second'])) {
    $variables['columns'] = 2;
  }
  else {
    $variables['columns'] = 1;
  }
}
