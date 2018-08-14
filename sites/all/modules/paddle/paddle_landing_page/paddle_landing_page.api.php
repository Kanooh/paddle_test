<?php

/**
 * @file
 * Paddle Landing Page API documentation.
 */

/**
 * Add view modes to display items within the menu structure pane.
 */
function hook_paddle_landing_page_menu_structure_view_modes() {
  // Returns an array keyed by the view mode value and containing a label.
  return array(
    'test_view' => t('Test View Mode'),
  );
}
