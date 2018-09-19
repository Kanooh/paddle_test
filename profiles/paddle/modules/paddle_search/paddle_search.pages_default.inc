<?php
/**
 * @file
 * paddle_search.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function paddle_search_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'pm_existing_pages_search_results_panel_context';
  $handler->task = 'pm_existing_pages';
  $handler->subtask = 'search_results';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
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
  $display->layout = 'paddle_2_col_3_9_flexible';
  $display->layout_settings = array(
    'items' => array(
      'canvas' => array(
        'type' => 'row',
        'contains' => 'column',
        'children' => array(
          0 => 'main',
        ),
        'parent' => NULL,
      ),
      'main' => array(
        'type' => 'column',
        'width' => 100,
        'width_type' => '%',
        'children' => array(
          0 => 'main-row',
        ),
        'parent' => 'canvas',
      ),
      'main-row' => array(
        'type' => 'row',
        'contains' => 'region',
        'children' => array(
          0 => 'left',
          1 => 'center',
        ),
        'parent' => 'main',
      ),
      'center' => array(
        'type' => 'region',
        'title' => 'Center',
        'width' => '72.13767632269835',
        'width_type' => '%',
        'parent' => 'main-row',
      ),
      'left' => array(
        'type' => 'region',
        'title' => 'left',
        'width' => '27.862323677301646',
        'width_type' => '%',
        'parent' => 'main-row',
        'class' => '',
      ),
    ),
  );
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'right' => NULL,
      'left' => NULL,
      'middle' => NULL,
      'center' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '251ec35d-9878-4c42-ae81-fe1eba1727bf';
  $display->storage_type = 'page_manager';
  $display->storage_id = 'pm_existing_pages_search_results_panel_context';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-6ccc7273-1a92-4e09-b9d5-314669cf3845';
  $pane->panel = 'left';
  $pane->type = 'additional_panes';
  $pane->subtype = 'additional_panes';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'search_results',
    'region' => 'sidebar',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
    'style' => 'naked',
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '6ccc7273-1a92-4e09-b9d5-314669cf3845';
  $display->content['new-6ccc7273-1a92-4e09-b9d5-314669cf3845'] = $pane;
  $display->panels['left'][0] = 'new-6ccc7273-1a92-4e09-b9d5-314669cf3845';
  $pane = new stdClass();
  $pane->pid = 'new-4e83aa9a-4721-45db-afea-b2cf6c65b82a';
  $pane->panel = 'right';
  $pane->type = 'additional_panes';
  $pane->subtype = 'additional_panes';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'search_results',
    'region' => 'results_top',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
    'style' => 'naked',
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '4e83aa9a-4721-45db-afea-b2cf6c65b82a';
  $display->content['new-4e83aa9a-4721-45db-afea-b2cf6c65b82a'] = $pane;
  $display->panels['right'][0] = 'new-4e83aa9a-4721-45db-afea-b2cf6c65b82a';
  $pane = new stdClass();
  $pane->pid = 'new-071861c5-0a22-45e4-a062-54c2ec409ab7';
  $pane->panel = 'right';
  $pane->type = 'pm_existing_pages';
  $pane->subtype = 'pm_existing_pages';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    0 => 'task_id',
    'task_id' => 'search_results',
    'override_title' => 1,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '071861c5-0a22-45e4-a062-54c2ec409ab7';
  $display->content['new-071861c5-0a22-45e4-a062-54c2ec409ab7'] = $pane;
  $display->panels['right'][1] = 'new-071861c5-0a22-45e4-a062-54c2ec409ab7';
  $pane = new stdClass();
  $pane->pid = 'new-8e785332-878d-4378-ba64-dd230040991b';
  $pane->panel = 'right';
  $pane->type = 'additional_panes';
  $pane->subtype = 'additional_panes';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'search_results',
    'region' => 'results_bottom',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
    'style' => 'naked',
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 2;
  $pane->locks = array();
  $pane->uuid = '8e785332-878d-4378-ba64-dd230040991b';
  $display->content['new-8e785332-878d-4378-ba64-dd230040991b'] = $pane;
  $display->panels['right'][2] = 'new-8e785332-878d-4378-ba64-dd230040991b';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = 'new-071861c5-0a22-45e4-a062-54c2ec409ab7';
  $handler->conf['display'] = $display;
  $export['pm_existing_pages_search_results_panel_context'] = $handler;

  return $export;
}
