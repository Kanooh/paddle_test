<?php
/**
 * @file
 * paddle_mega_dropdown.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_mega_dropdown_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'paddle_mega_dropdown:paddle_mega_dropdown:paddle_2_col_6_6';
  $panelizer->title = 'paddle_2_col_6_6';
  $panelizer->panelizer_type = 'paddle_mega_dropdown';
  $panelizer->panelizer_key = 'paddle_mega_dropdown';
  $panelizer->no_blocks = FALSE;
  $panelizer->css_id = '';
  $panelizer->css = '';
  $panelizer->pipeline = 'paddle_mega_dropdown';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $panelizer->access = array(
    0 => 'administer panelizer paddle_mega_dropdown paddle_mega_dropdown content',
  );
  $panelizer->view_mode = 'page_manager';
  $panelizer->css_class = '';
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $display = new panels_display();
  $display->layout = 'paddle_2_col_6_6';
  $display->layout_settings = array();
  $display->panel_settings = array();
  $display->cache = array();
  $display->title = '%paddle_mega_dropdown:composed-label';
  $display->uuid = '8ccb40cc-b486-c304-8112-73d67ca9b203';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['paddle_mega_dropdown:paddle_mega_dropdown:paddle_2_col_6_6'] = $panelizer;

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'paddle_mega_dropdown:paddle_mega_dropdown:paddle_4_col_full';
  $panelizer->title = 'paddle_4_col_full';
  $panelizer->panelizer_type = 'paddle_mega_dropdown';
  $panelizer->panelizer_key = 'paddle_mega_dropdown';
  $panelizer->no_blocks = FALSE;
  $panelizer->css_id = '';
  $panelizer->css = '';
  $panelizer->pipeline = 'paddle_mega_dropdown';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $panelizer->access = array(
    0 => 'administer panelizer paddle_mega_dropdown paddle_mega_dropdown content',
  );
  $panelizer->view_mode = 'page_manager';
  $panelizer->css_class = '';
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $display = new panels_display();
  $display->layout = 'paddle_4_col_full';
  $display->layout_settings = array();
  $display->panel_settings = array();
  $display->cache = array();
  $display->title = '%paddle_mega_dropdown:composed-label';
  $display->uuid = '18c558cb-bd69-6454-fdab-bd806a1db65d';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['paddle_mega_dropdown:paddle_mega_dropdown:paddle_4_col_full'] = $panelizer;

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'paddle_mega_dropdown:paddle_mega_dropdown:paddle_three_column';
  $panelizer->title = 'paddle_three_column';
  $panelizer->panelizer_type = 'paddle_mega_dropdown';
  $panelizer->panelizer_key = 'paddle_mega_dropdown';
  $panelizer->no_blocks = FALSE;
  $panelizer->css_id = '';
  $panelizer->css = '';
  $panelizer->pipeline = 'paddle_mega_dropdown';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $panelizer->access = array(
    0 => 'administer panelizer paddle_mega_dropdown paddle_mega_dropdown content',
  );
  $panelizer->view_mode = 'page_manager';
  $panelizer->css_class = '';
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $display = new panels_display();
  $display->layout = 'paddle_three_column';
  $display->layout_settings = array();
  $display->panel_settings = array();
  $display->cache = array();
  $display->title = '%paddle_mega_dropdown:composed-label';
  $display->uuid = '9f001fd6-ee0e-dbe4-213d-091db4d33cf8';
  $display->content = array();
  $display->panels = array();
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['paddle_mega_dropdown:paddle_mega_dropdown:paddle_three_column'] = $panelizer;

  return $export;
}
