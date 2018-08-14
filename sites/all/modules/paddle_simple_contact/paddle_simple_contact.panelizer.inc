<?php
/**
 * @file
 * paddle_simple_contact.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_simple_contact_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:simple_contact_page:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'simple_contact_page';
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
  $display->uuid = '1ec2d7aa-4173-21e4-19b1-0d6477996e27';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-1053ca9c-82d4-a874-5576-32f9b4ab7956';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'simple_contact_page',
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
  $pane->uuid = '1053ca9c-82d4-a874-5576-32f9b4ab7956';
  $display->content['new-1053ca9c-82d4-a874-5576-32f9b4ab7956'] = $pane;
  $display->panels['bottom'][0] = 'new-1053ca9c-82d4-a874-5576-32f9b4ab7956';
  $pane = new stdClass();
  $pane->pid = 'new-1e642791-6103-68d4-4541-f8f59dc19e3e';
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
  $pane->uuid = '1e642791-6103-68d4-4541-f8f59dc19e3e';
  $display->content['new-1e642791-6103-68d4-4541-f8f59dc19e3e'] = $pane;
  $display->panels['left'][0] = 'new-1e642791-6103-68d4-4541-f8f59dc19e3e';
  $pane = new stdClass();
  $pane->pid = 'new-5bddb068-f341-0814-c1f1-5bca3d3d2864';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'simple_contact_page',
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
  $pane->uuid = '5bddb068-f341-0814-c1f1-5bca3d3d2864';
  $display->content['new-5bddb068-f341-0814-c1f1-5bca3d3d2864'] = $pane;
  $display->panels['right'][0] = 'new-5bddb068-f341-0814-c1f1-5bca3d3d2864';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:simple_contact_page:default'] = $panelizer;

  return $export;
}
