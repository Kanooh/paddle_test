<?php
/**
 * @file
 * paddle_teams.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_teams_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'teams';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:teams:default';
  $panelizer->css_id = '';
  $panelizer->css_class = '';
  $panelizer->css = '';
  $panelizer->no_blocks = FALSE;
  $panelizer->title_element = 'H2';
  $panelizer->link_to_entity = TRUE;
  $panelizer->extra = array();
  $panelizer->pipeline = 'paddle_landing_page';
  $panelizer->contexts = array();
  $panelizer->relationships = array();
  $display = new panels_display();
  $display->layout = 'paddle_3_col_c';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'center' => NULL,
      'top' => NULL,
      '1_a' => NULL,
      '2_a' => NULL,
      '3_b' => NULL,
      '4_b' => NULL,
      '5_b' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '064d43d7-d2e8-4678-be4d-317eeac416b6';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:teams:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-65e5a640-b2c6-43dd-98ef-28be4c8db1f6';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:body';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'text_default',
    'delta_limit' => 0,
    'delta_offset' => '0',
    'delta_reversed' => FALSE,
    'formatter_settings' => array(),
    'context' => 'panelizer',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '65e5a640-b2c6-43dd-98ef-28be4c8db1f6';
  $display->content['new-65e5a640-b2c6-43dd-98ef-28be4c8db1f6'] = $pane;
  $display->panels['1_a'][0] = 'new-65e5a640-b2c6-43dd-98ef-28be4c8db1f6';
  $pane = new stdClass();
  $pane->pid = 'new-d556ef87-0550-471e-b105-36f81ad33e16';
  $pane->panel = 'top';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_featured_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'title',
    'formatter' => 'paddle_scald_atom_default',
    'delta_limit' => 0,
    'delta_offset' => '0',
    'delta_reversed' => FALSE,
    'formatter_settings' => array(),
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
  $pane->uuid = 'd556ef87-0550-471e-b105-36f81ad33e16';
  $display->content['new-d556ef87-0550-471e-b105-36f81ad33e16'] = $pane;
  $display->panels['top'][0] = 'new-d556ef87-0550-471e-b105-36f81ad33e16';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-65e5a640-b2c6-43dd-98ef-28be4c8db1f6';
  $panelizer->display = $display;
  $export['node:teams:default'] = $panelizer;

  return $export;
}
