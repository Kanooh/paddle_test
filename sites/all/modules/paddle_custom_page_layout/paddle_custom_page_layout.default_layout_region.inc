<?php
/**
 * @file
 * paddle_custom_page_layout.default_layout_region.inc
 */

/**
 * Implements hook_default_layout_region().
 */
function paddle_custom_page_layout_default_layout_region() {
  $export = array();

  $layout_region = new stdClass();
  $layout_region->disabled = FALSE; /* Edit this to true to make a default layout_region disabled initially */
  $layout_region->api_version = 1;
  $layout_region->name = 'a';
  $layout_region->admin_title = 'A';
  $export['a'] = $layout_region;

  $layout_region = new stdClass();
  $layout_region->disabled = FALSE; /* Edit this to true to make a default layout_region disabled initially */
  $layout_region->api_version = 1;
  $layout_region->name = 'b';
  $layout_region->admin_title = 'B';
  $export['b'] = $layout_region;

  $layout_region = new stdClass();
  $layout_region->disabled = FALSE; /* Edit this to true to make a default layout_region disabled initially */
  $layout_region->api_version = 1;
  $layout_region->name = 'c';
  $layout_region->admin_title = 'C';
  $export['c'] = $layout_region;

  $layout_region = new stdClass();
  $layout_region->disabled = FALSE; /* Edit this to true to make a default layout_region disabled initially */
  $layout_region->api_version = 1;
  $layout_region->name = 'd';
  $layout_region->admin_title = 'D';
  $export['d'] = $layout_region;

  $layout_region = new stdClass();
  $layout_region->disabled = FALSE; /* Edit this to true to make a default layout_region disabled initially */
  $layout_region->api_version = 1;
  $layout_region->name = 'e';
  $layout_region->admin_title = 'E';
  $export['e'] = $layout_region;

  return $export;
}
