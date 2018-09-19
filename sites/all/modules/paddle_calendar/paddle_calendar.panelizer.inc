<?php
/**
 * @file
 * paddle_calendar.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_calendar_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:calendar_item:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'calendar_item';
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
  $display->uuid = 'c83b8790-eb8a-4330-a213-9123e20b83fa';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-9491675e-2ac4-447e-aea9-91620d100006';
    $pane->panel = 'bottom';
    $pane->type = 'content_region';
    $pane->subtype = 'content_region';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'right',
      'type' => 'calendar_item',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '9491675e-2ac4-447e-aea9-91620d100006';
    $display->content['new-9491675e-2ac4-447e-aea9-91620d100006'] = $pane;
    $display->panels['bottom'][0] = 'new-9491675e-2ac4-447e-aea9-91620d100006';
    $pane = new stdClass();
    $pane->pid = 'new-13fe0f36-87a7-401f-97f0-74f805e9bfa2';
    $pane->panel = 'left';
    $pane->type = 'entity_revision_view';
    $pane->subtype = 'node';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'panelizer',
      'override_title' => 0,
      'override_title_text' => '',
      'override_title_heading' => 'h2',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '13fe0f36-87a7-401f-97f0-74f805e9bfa2';
    $display->content['new-13fe0f36-87a7-401f-97f0-74f805e9bfa2'] = $pane;
    $display->panels['left'][0] = 'new-13fe0f36-87a7-401f-97f0-74f805e9bfa2';
    $pane = new stdClass();
    $pane->pid = 'new-c632acb6-725b-4cde-9711-073d75d101a9';
    $pane->panel = 'right';
    $pane->type = 'content_region';
    $pane->subtype = 'content_region';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'bottom',
      'type' => 'calendar_item',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'c632acb6-725b-4cde-9711-073d75d101a9';
    $display->content['new-c632acb6-725b-4cde-9711-073d75d101a9'] = $pane;
    $display->panels['right'][0] = 'new-c632acb6-725b-4cde-9711-073d75d101a9';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:calendar_item:default'] = $panelizer;

  return $export;
}
