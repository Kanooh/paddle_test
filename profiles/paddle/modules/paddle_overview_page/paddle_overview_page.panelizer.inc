<?php
/**
 * @file
 * paddle_overview_page.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_overview_page_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:paddle_overview_page:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_overview_page';
  $panelizer->no_blocks = FALSE;
  $panelizer->css_id = '';
  $panelizer->css = '';
  $panelizer->pipeline = 'paddle_content_region';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->css_class = '';
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $display = new panels_display();
  $display->layout = 'paddle_2_col_9_3_bottom';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'right' => NULL,
      'left' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '91632a51-d263-4eab-aac4-4ac3cbe0ae44';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-b8265173-441e-4779-9e83-4d696befbadb';
    $pane->panel = 'left';
    $pane->type = 'entity_view';
    $pane->subtype = 'node';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'panelizer',
      'override_title' => 0,
      'override_title_text' => '',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array(
      'type' => 'immovable',
      'regions' => array(
        'bottom' => 'bottom',
      ),
    );
    $pane->uuid = 'b8265173-441e-4779-9e83-4d696befbadb';
    $display->content['new-b8265173-441e-4779-9e83-4d696befbadb'] = $pane;
    $display->panels['left'][0] = 'new-b8265173-441e-4779-9e83-4d696befbadb';
    $pane = new stdClass();
    $pane->pid = 'new-42812249-4ab1-41b0-8b96-8639f6e5562f';
    $pane->panel = 'left';
    $pane->type = 'additional_panes';
    $pane->subtype = 'additional_panes';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => 'overview_page_view',
      'region' => 'main',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '42812249-4ab1-41b0-8b96-8639f6e5562f';
    $display->content['new-42812249-4ab1-41b0-8b96-8639f6e5562f'] = $pane;
    $display->panels['left'][1] = 'new-42812249-4ab1-41b0-8b96-8639f6e5562f';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:paddle_overview_page:default'] = $panelizer;

  return $export;
}
