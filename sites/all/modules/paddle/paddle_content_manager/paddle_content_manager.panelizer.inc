<?php
/**
 * @file
 * paddle_content_manager.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_content_manager_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'basic_page';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:basic_page:default';
  $panelizer->css_id = '';
  $panelizer->css_class = '';
  $panelizer->css = '';
  $panelizer->no_blocks = FALSE;
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $panelizer->pipeline = 'paddle_content_region';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
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
  $display->uuid = 'd6dd4374-6779-4ecc-8a41-3c431971e3fd';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:basic_page:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-3128e3f8-5d72-f764-9d13-8767a7480085';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'basic_page',
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
  $pane->uuid = '3128e3f8-5d72-f764-9d13-8767a7480085';
  $display->content['new-3128e3f8-5d72-f764-9d13-8767a7480085'] = $pane;
  $display->panels['bottom'][0] = 'new-3128e3f8-5d72-f764-9d13-8767a7480085';
  $pane = new stdClass();
  $pane->pid = 'new-733a4fa8-4afc-ff94-d15b-c4fa54e37edf';
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
      'left' => 'left',
    ),
  );
  $pane->uuid = '733a4fa8-4afc-ff94-d15b-c4fa54e37edf';
  $display->content['new-733a4fa8-4afc-ff94-d15b-c4fa54e37edf'] = $pane;
  $display->panels['left'][0] = 'new-733a4fa8-4afc-ff94-d15b-c4fa54e37edf';
  $pane = new stdClass();
  $pane->pid = 'new-b2e734be-4a7b-6c44-a59f-221ea5c1ccb1';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'basic_page',
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
      'right' => 'right',
    ),
  );
  $pane->uuid = 'b2e734be-4a7b-6c44-a59f-221ea5c1ccb1';
  $display->content['new-b2e734be-4a7b-6c44-a59f-221ea5c1ccb1'] = $pane;
  $display->panels['right'][0] = 'new-b2e734be-4a7b-6c44-a59f-221ea5c1ccb1';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:basic_page:default'] = $panelizer;

  return $export;
}
