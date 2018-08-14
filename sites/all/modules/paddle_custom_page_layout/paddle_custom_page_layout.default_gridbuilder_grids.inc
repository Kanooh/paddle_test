<?php
/**
 * @file
 * paddle_custom_page_layout.default_gridbuilder_grids.inc
 */

/**
 * Implements hook_default_gridbuilder_grids().
 */
function paddle_custom_page_layout_default_gridbuilder_grids() {
  $export = array();

  $gridbuilder = new stdClass();
  $gridbuilder->disabled = FALSE; /* Edit this to true to make a default gridbuilder disabled initially */
  $gridbuilder->api_version = 1;
  $gridbuilder->name = 'twelve_column_fluid';
  $gridbuilder->admin_title = 'Twelve column fluid';
  $gridbuilder->grid_type = 1;
  $gridbuilder->width = 100;
  $gridbuilder->columns = 12;
  $gridbuilder->padding_width = 0;
  $gridbuilder->gutter_width = 3;

  $gridbuilder = new stdClass();
  $gridbuilder->disabled = FALSE; /* Edit this to true to make a default gridbuilder disabled initially */
  $gridbuilder->api_version = 1;
  $gridbuilder->name = 'three_column_fluid';
  $gridbuilder->admin_title = 'Three column fluid';
  $gridbuilder->grid_type = 1;
  $gridbuilder->width = 100;
  $gridbuilder->columns = 3;
  $gridbuilder->padding_width = 0;
  $gridbuilder->gutter_width = 3;

  $gridbuilder = new stdClass();
  $gridbuilder->disabled = FALSE; /* Edit this to true to make a default gridbuilder disabled initially */
  $gridbuilder->api_version = 1;
  $gridbuilder->name = 'six_column_fluid';
  $gridbuilder->admin_title = 'Six column fluid';
  $gridbuilder->grid_type = 1;
  $gridbuilder->width = 100;
  $gridbuilder->columns = 6;
  $gridbuilder->padding_width = 0;
  $gridbuilder->gutter_width = 3;

  return $export;
}
