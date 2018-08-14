<?php
/**
 * @file
 * paddle_product.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_product_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:paddle_product:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_product';
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
  $display->uuid = 'facb10c1-fd0d-4730-8075-69d05bd38aeb';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-f88b285a-d802-424e-8d5e-0c3f17ffe0f8';
    $pane->panel = 'bottom';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'bottom',
      'type' => 'paddle_product',
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
    $pane->uuid = 'f88b285a-d802-424e-8d5e-0c3f17ffe0f8';
    $display->content['new-f88b285a-d802-424e-8d5e-0c3f17ffe0f8'] = $pane;
    $display->panels['bottom'][0] = 'new-f88b285a-d802-424e-8d5e-0c3f17ffe0f8';
    $pane = new stdClass();
    $pane->pid = 'new-55c6d247-6046-4a8c-bb57-a501c43a2cb5';
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
    $pane->uuid = '55c6d247-6046-4a8c-bb57-a501c43a2cb5';
    $display->content['new-55c6d247-6046-4a8c-bb57-a501c43a2cb5'] = $pane;
    $display->panels['left'][0] = 'new-55c6d247-6046-4a8c-bb57-a501c43a2cb5';
    $pane = new stdClass();
    $pane->pid = 'new-30910c69-4e12-4763-bc4e-f907874764aa';
    $pane->panel = 'right';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'right',
      'type' => 'paddle_product',
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
    $pane->uuid = '30910c69-4e12-4763-bc4e-f907874764aa';
    $display->content['new-30910c69-4e12-4763-bc4e-f907874764aa'] = $pane;
    $display->panels['right'][0] = 'new-30910c69-4e12-4763-bc4e-f907874764aa';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:paddle_product:default'] = $panelizer;

  return $export;
}
