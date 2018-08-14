<?php
/**
 * @file
 * paddle_quiz.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_quiz_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->name = 'node:quiz_page:default';
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'quiz_page';
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
  $display->uuid = '3eb251a2-02a3-4614-88fb-2468325243b8';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-6ff699c8-25ba-4983-b5a8-68a693eecd60';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'quiz_page',
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
  $pane->uuid = '6ff699c8-25ba-4983-b5a8-68a693eecd60';
  $display->content['new-6ff699c8-25ba-4983-b5a8-68a693eecd60'] = $pane;
  $display->panels['bottom'][0] = 'new-6ff699c8-25ba-4983-b5a8-68a693eecd60';
  $pane = new stdClass();
  $pane->pid = 'new-cffc6972-62ad-47de-abcf-ab156c917845';
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
  $pane->uuid = 'cffc6972-62ad-47de-abcf-ab156c917845';
  $display->content['new-cffc6972-62ad-47de-abcf-ab156c917845'] = $pane;
  $display->panels['left'][0] = 'new-cffc6972-62ad-47de-abcf-ab156c917845';
  $pane = new stdClass();
  $pane->pid = 'new-49e24517-e000-4144-88f3-8e1754271e2c';
  $pane->panel = 'left';
  $pane->type = 'paddle_quiz';
  $pane->subtype = 'paddle_quiz';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'panelizer',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '49e24517-e000-4144-88f3-8e1754271e2c';
  $display->content['new-49e24517-e000-4144-88f3-8e1754271e2c'] = $pane;
  $display->panels['left'][1] = 'new-49e24517-e000-4144-88f3-8e1754271e2c';
  $pane = new stdClass();
  $pane->pid = 'new-7f663172-44a5-47c7-9da7-60464f4c0e1e';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'quiz_page',
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
  $pane->uuid = '7f663172-44a5-47c7-9da7-60464f4c0e1e';
  $display->content['new-7f663172-44a5-47c7-9da7-60464f4c0e1e'] = $pane;
  $display->panels['right'][0] = 'new-7f663172-44a5-47c7-9da7-60464f4c0e1e';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:quiz_page:default'] = $panelizer;

  return $export;
}
