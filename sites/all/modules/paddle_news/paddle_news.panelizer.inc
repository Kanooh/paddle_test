<?php
/**
 * @file
 * paddle_news.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_news_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:news_item:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'news_item';
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
  $display->uuid = '51c54b08-a0c7-49ae-89b9-e96fd937624a';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-b4bbbc80-f2a5-4e38-b5d9-53e32de54403';
    $pane->panel = 'bottom';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'bottom',
      'type' => 'news_item',
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
    $pane->uuid = 'b4bbbc80-f2a5-4e38-b5d9-53e32de54403';
    $display->content['new-b4bbbc80-f2a5-4e38-b5d9-53e32de54403'] = $pane;
    $display->panels['bottom'][0] = 'new-b4bbbc80-f2a5-4e38-b5d9-53e32de54403';
    $pane = new stdClass();
    $pane->pid = 'new-984bae9d-6453-41a6-8340-237b35b831ce';
    $pane->panel = 'left';
    $pane->type = 'news_info_banner';
    $pane->subtype = 'news_info_banner';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
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
    $pane->uuid = '984bae9d-6453-41a6-8340-237b35b831ce';
    $display->content['new-984bae9d-6453-41a6-8340-237b35b831ce'] = $pane;
    $display->panels['left'][0] = 'new-984bae9d-6453-41a6-8340-237b35b831ce';
    $pane = new stdClass();
    $pane->pid = 'new-5d7ebe3b-df8b-4ed6-b4bd-4cbeb05e05e1';
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
    $pane->position = 1;
    $pane->locks = array(
      'type' => 'immovable',
      'regions' => array(
        'left' => 'left',
      ),
    );
    $pane->uuid = '5d7ebe3b-df8b-4ed6-b4bd-4cbeb05e05e1';
    $display->content['new-5d7ebe3b-df8b-4ed6-b4bd-4cbeb05e05e1'] = $pane;
    $display->panels['left'][1] = 'new-5d7ebe3b-df8b-4ed6-b4bd-4cbeb05e05e1';
    $pane = new stdClass();
    $pane->pid = 'new-bc10cf82-b046-4b32-9889-a67bc051e272';
    $pane->panel = 'right';
    $pane->type = 'content_region';
    $pane->subtype = 'inherit';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'region' => 'right',
      'type' => 'news_item',
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
    $pane->uuid = 'bc10cf82-b046-4b32-9889-a67bc051e272';
    $display->content['new-bc10cf82-b046-4b32-9889-a67bc051e272'] = $pane;
    $display->panels['right'][0] = 'new-bc10cf82-b046-4b32-9889-a67bc051e272';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:news_item:default'] = $panelizer;

  return $export;
}
