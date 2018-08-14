<?php
/**
 * @file
 * paddle_pane_collection.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_pane_collection_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'paddle_no_column';
  $panelizer->panelizer_type = 'paddle_pane_collection';
  $panelizer->panelizer_key = 'paddle_pane_collection';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'paddle_pane_collection:paddle_pane_collection:paddle_no_column';
  $panelizer->css_id = '';
  $panelizer->css_class = '';
  $panelizer->css = '';
  $panelizer->no_blocks = FALSE;
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $panelizer->pipeline = 'paddle_pane_collection';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $display = new panels_display();
  $display->layout = 'paddle_no_column';
  $display->layout_settings = array();
  $display->panel_settings = array();
  $display->cache = array();
  $display->title = '';
  $display->uuid = 'b73a9695-0b20-4641-8583-d3d6e55847d6';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'paddle_pane_collection:paddle_pane_collection:paddle_no_column';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['paddle_pane_collection:paddle_pane_collection:paddle_no_column'] = $panelizer;

  return $export;
}
