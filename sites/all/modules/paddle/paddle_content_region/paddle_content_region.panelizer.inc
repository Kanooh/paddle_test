<?php
/**
 * @file
 * paddle_content_region.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_content_region_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'paddle_content_region:paddle_content_region:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'paddle_content_region';
  $panelizer->panelizer_key = 'paddle_content_region';
  $panelizer->no_blocks = FALSE;
  $panelizer->css_id = '';
  $panelizer->css = '';
  $panelizer->pipeline = 'paddle_content_region';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $panelizer->access = array('administer panelizer paddle_content_region paddle_content_region content');
  $panelizer->view_mode = 'page_manager';
  $panelizer->css_class = '';
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $display = new panels_display();
  $display->layout = 'paddle_2_col_9_3_bottom';
  $display->layout_settings = array();
  $display->panel_settings = array();
  $display->cache = array();
  $display->title = '%paddle_content_region:composed-label';
  $display->uuid = 'fc9d2d59-88a0-2994-3d84-afaf054077b5';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['paddle_content_region:paddle_content_region:default'] = $panelizer;

  return $export;
}
