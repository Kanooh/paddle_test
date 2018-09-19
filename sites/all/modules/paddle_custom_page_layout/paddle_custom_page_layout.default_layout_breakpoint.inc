<?php
/**
 * @file
 * paddle_custom_page_layout.default_layout_breakpoint.inc
 */

/**
 * Implements hook_default_layout_breakpoint().
 */
function paddle_custom_page_layout_default_layout_breakpoint() {
  $export = array();

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = FALSE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'desktop';
  $layout_breakpoint->admin_title = 'Desktop';
  $layout_breakpoint->width = '1170px';
  $layout_breakpoint->grid_name = 'twelve_column_fluid';
  $export['desktop'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = TRUE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'smartphone';
  $layout_breakpoint->admin_title = 'Smartphone';
  $layout_breakpoint->width = '0px';
  $layout_breakpoint->grid_name = 'three_column_fluid';
  $export['smartphone'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = FALSE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'smartphone_device';
  $layout_breakpoint->admin_title = 'Smartphone';
  $layout_breakpoint->width = '0px';
  $layout_breakpoint->grid_name = 'three_column_fluid';
  $export['smartphone_device'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = TRUE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'standard';
  $layout_breakpoint->admin_title = 'Standard';
  $layout_breakpoint->width = '1171px';
  $layout_breakpoint->grid_name = 'twelve_column_fluid';
  $export['standard'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = TRUE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'tablet';
  $layout_breakpoint->admin_title = 'Tablet portrait';
  $layout_breakpoint->width = '579px';
  $layout_breakpoint->grid_name = 'six_column_fluid';
  $export['tablet'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = FALSE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'tablet_landscape_modus';
  $layout_breakpoint->admin_title = 'Tablet landscape';
  $layout_breakpoint->width = '889px';
  $layout_breakpoint->grid_name = 'twelve_column_fluid';
  $export['tablet_landscape_modus'] = $layout_breakpoint;

  $layout_breakpoint = new stdClass();
  $layout_breakpoint->disabled = FALSE; /* Edit this to true to make a default layout_breakpoint disabled initially */
  $layout_breakpoint->api_version = 1;
  $layout_breakpoint->name = 'tablet_portrait_modus';
  $layout_breakpoint->admin_title = 'Tablet portrait';
  $layout_breakpoint->width = '579px';
  $layout_breakpoint->grid_name = 'six_column_fluid';
  $export['tablet_portrait_modus'] = $layout_breakpoint;

  return $export;
}
