<?php
/**
 * @file
 * paddle_formbuilder.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_formbuilder_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:paddle_formbuilder_page:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_formbuilder_page';
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
      'center' => NULL,
      'right' => NULL,
      'left' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '6015d3cd-54c3-486c-9f83-3c5192728df9';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-fecfd9a1-4a02-46bf-8c80-cbf251cfe023';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'paddle_formbuilder_page',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = 'fecfd9a1-4a02-46bf-8c80-cbf251cfe023';
  $display->content['new-fecfd9a1-4a02-46bf-8c80-cbf251cfe023'] = $pane;
  $display->panels['bottom'][0] = 'new-fecfd9a1-4a02-46bf-8c80-cbf251cfe023';
  $pane = new stdClass();
  $pane->pid = 'new-77dc1732-aa55-4771-b2a6-449f039395f3';
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
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'bottom' => 'bottom',
    ),
  );
  $pane->uuid = '77dc1732-aa55-4771-b2a6-449f039395f3';
  $display->content['new-77dc1732-aa55-4771-b2a6-449f039395f3'] = $pane;
  $display->panels['left'][0] = 'new-77dc1732-aa55-4771-b2a6-449f039395f3';
  $pane = new stdClass();
  $pane->pid = 'new-a52bd753-baf6-49ea-ab77-4faf168e4e17';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'paddle_formbuilder_page',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = 'a52bd753-baf6-49ea-ab77-4faf168e4e17';
  $display->content['new-a52bd753-baf6-49ea-ab77-4faf168e4e17'] = $pane;
  $display->panels['right'][0] = 'new-a52bd753-baf6-49ea-ab77-4faf168e4e17';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:paddle_formbuilder_page:default'] = $panelizer;

  return $export;
}
