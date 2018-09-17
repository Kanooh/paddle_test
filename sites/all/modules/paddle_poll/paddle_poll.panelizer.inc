<?php
/**
 * @file
 * paddle_poll.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_poll_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:poll:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'poll';
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
      'nested_top' => NULL,
      'nested_left' => NULL,
      'nested_right' => NULL,
      'right' => NULL,
      'bottom' => NULL,
      'left' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = 'fc1e8285-a56a-42bc-b6c0-afb0dae5ecae';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-6fd35eee-b542-478b-ad14-023a09638529';
    $pane->panel = 'bottom';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'bottom',
      'type' => 'poll',
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
    $pane->uuid = '6fd35eee-b542-478b-ad14-023a09638529';
    $display->content['new-6fd35eee-b542-478b-ad14-023a09638529'] = $pane;
    $display->panels['bottom'][0] = 'new-6fd35eee-b542-478b-ad14-023a09638529';
    $pane = new stdClass();
    $pane->pid = 'new-7f401d52-ed96-4a7e-ae68-68341f7506dd';
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
    $pane->uuid = '7f401d52-ed96-4a7e-ae68-68341f7506dd';
    $display->content['new-7f401d52-ed96-4a7e-ae68-68341f7506dd'] = $pane;
    $display->panels['left'][0] = 'new-7f401d52-ed96-4a7e-ae68-68341f7506dd';
    $pane = new stdClass();
    $pane->pid = 'new-e0e73af7-5f87-4052-a134-451fb1b9334b';
    $pane->panel = 'right';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'right',
      'type' => 'poll',
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
    $pane->uuid = 'e0e73af7-5f87-4052-a134-451fb1b9334b';
    $display->content['new-e0e73af7-5f87-4052-a134-451fb1b9334b'] = $pane;
    $display->panels['right'][0] = 'new-e0e73af7-5f87-4052-a134-451fb1b9334b';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-e0e73af7-5f87-4052-a134-451fb1b9334b';
  $panelizer->display = $display;
  $export['node:poll:default'] = $panelizer;

  return $export;
}
