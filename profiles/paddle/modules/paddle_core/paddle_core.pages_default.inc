<?php
/**
 * @file
 * paddle_core.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function paddle_core_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'node_view_panel_context';
  $handler->task = 'node_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = -28;
  $handler->conf = array(
    'title' => 'Default',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
  );
  $display = new panels_display();
  $display->layout = 'paddle_2_col_9_3';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'right' => NULL,
      'left' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '8f95185d-fba7-2bf4-55d9-857a6cde9351';
  $display->storage_type = 'page_manager';
  $display->storage_id = 'node_view_panel_context';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-6890d07c-4088-4104-21ae-79d8dcafc8e8';
  $pane->panel = 'left';
  $pane->type = 'entity_revision_view';
  $pane->subtype = 'node';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'view_mode' => 'full',
    'context' => 'argument_entity_id:node_1',
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
  $pane->locks = array();
  $pane->uuid = '6890d07c-4088-4104-21ae-79d8dcafc8e8';
  $display->content['new-6890d07c-4088-4104-21ae-79d8dcafc8e8'] = $pane;
  $display->panels['left'][0] = 'new-6890d07c-4088-4104-21ae-79d8dcafc8e8';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-6890d07c-4088-4104-21ae-79d8dcafc8e8';
  $handler->conf['display'] = $display;
  $export['node_view_panel_context'] = $handler;

  return $export;
}
