<?php
/**
 * @file
 * paddle_publication.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_publication_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_publication';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:paddle_publication:default';
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
  $display->uuid = '58daec61-56f6-4ffb-93db-26316d6cd82f';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:paddle_publication:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-42e94e8d-efd2-4529-8650-f0e0414ad56f';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'content_region';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'paddle_publication',
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
  $pane->uuid = '42e94e8d-efd2-4529-8650-f0e0414ad56f';
  $display->content['new-42e94e8d-efd2-4529-8650-f0e0414ad56f'] = $pane;
  $display->panels['bottom'][0] = 'new-42e94e8d-efd2-4529-8650-f0e0414ad56f';
  $pane = new stdClass();
  $pane->pid = 'new-03c09304-bd0d-4cd1-a98a-7efcf19e387c';
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
  $pane->uuid = '03c09304-bd0d-4cd1-a98a-7efcf19e387c';
  $display->content['new-03c09304-bd0d-4cd1-a98a-7efcf19e387c'] = $pane;
  $display->panels['left'][0] = 'new-03c09304-bd0d-4cd1-a98a-7efcf19e387c';
  $pane = new stdClass();
  $pane->pid = 'new-b0321c06-5585-4a17-b845-3bf34be5916c';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'content_region';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'paddle_publication',
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
  $pane->uuid = 'b0321c06-5585-4a17-b845-3bf34be5916c';
  $display->content['new-b0321c06-5585-4a17-b845-3bf34be5916c'] = $pane;
  $display->panels['right'][0] = 'new-b0321c06-5585-4a17-b845-3bf34be5916c';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:paddle_publication:default'] = $panelizer;

  return $export;
}
