<?php
/**
 * @file
 * paddle_content_manager.panels_default.inc
 */

/**
 * Implements hook_default_panels_mini().
 */
function paddle_content_manager_default_panels_mini() {
  $export = array();

  $mini = new stdClass();
  $mini->disabled = FALSE; /* Edit this to true to make a default mini disabled initially */
  $mini->api_version = 1;
  $mini->name = 'basic_options';
  $mini->category = '';
  $mini->admin_title = 'Navigation';
  $mini->admin_description = 'This are the basic options shown on the node edit/create form.';
  $mini->requiredcontexts = array(
    0 => array(
      'identifier' => 'Node edit form',
      'keyword' => 'node_edit',
      'name' => 'node_edit_form',
      'nid' => '',
      'id' => 1,
    ),
  );
  $mini->contexts = array();
  $mini->relationships = array();
  $display = new panels_display();
  $display->layout = 'onecol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'middle' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = 'Navigation';
  $display->uuid = 'ca5e2b3a-ed1e-9414-b5cb-ea24cd3595ea';
  $display->storage_type = 'panels_mini';
  $display->storage_id = 'basic_options';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-d0072b20-7f1c-455b-b25d-e36497cba75e';
  $pane->panel = 'middle';
  $pane->type = 'node_menu_items';
  $pane->subtype = 'node_menu_items';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'requiredcontext_node_edit_form_1',
    'override_title' => 1,
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
  $pane->uuid = 'd0072b20-7f1c-455b-b25d-e36497cba75e';
  $display->content['new-d0072b20-7f1c-455b-b25d-e36497cba75e'] = $pane;
  $display->panels['middle'][0] = 'new-d0072b20-7f1c-455b-b25d-e36497cba75e';
  $pane = new stdClass();
  $pane->pid = 'new-ed7cd379-5749-c804-89cc-1816f3c6900e';
  $pane->panel = 'middle';
  $pane->type = 'node_form_path';
  $pane->subtype = 'node_form_path';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'requiredcontext_node_edit_form_1',
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
  $pane->uuid = 'ed7cd379-5749-c804-89cc-1816f3c6900e';
  $display->content['new-ed7cd379-5749-c804-89cc-1816f3c6900e'] = $pane;
  $display->panels['middle'][1] = 'new-ed7cd379-5749-c804-89cc-1816f3c6900e';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-d0072b20-7f1c-455b-b25d-e36497cba75e';
  $mini->display = $display;
  $export['basic_options'] = $mini;

  $mini = new stdClass();
  $mini->disabled = FALSE; /* Edit this to true to make a default mini disabled initially */
  $mini->api_version = 1;
  $mini->name = 'taxonomy_vocabularies';
  $mini->category = 'Mini panels';
  $mini->admin_title = 'Taxonomy vocabularies';
  $mini->admin_description = 'Taxonomy vocabularies';
  $mini->requiredcontexts = array(
    0 => array(
      'identifier' => 'Node edit form',
      'keyword' => 'node_edit',
      'name' => 'node_edit_form',
      'nid' => '',
      'id' => 1,
    ),
  );
  $mini->contexts = array();
  $mini->relationships = array();
  $display = new panels_display();
  $display->layout = 'onecol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'middle' => NULL,
    ),
    'middle' => array(
      'style' => 'naked',
    ),
  );
  $display->cache = array();
  $display->title = 'Taxonomy';
  $display->uuid = '5e3a5825-748a-f574-f9cb-2e146b8c6240';
  $display->storage_type = 'panels_mini';
  $display->storage_id = 'taxonomy_vocabularies';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-fcca0d13-8819-8224-7933-2e3394760a77';
  $pane->panel = 'middle';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_tags';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'requiredcontext_node_edit_form_1',
    'override_title' => 0,
    'override_title_text' => '',
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
  $pane->uuid = 'fcca0d13-8819-8224-7933-2e3394760a77';
  $display->content['new-fcca0d13-8819-8224-7933-2e3394760a77'] = $pane;
  $display->panels['middle'][0] = 'new-fcca0d13-8819-8224-7933-2e3394760a77';
  $pane = new stdClass();
  $pane->pid = 'new-7da4c079-23be-3d34-7157-4ddcccd34c47';
  $pane->panel = 'middle';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_general_tags';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'requiredcontext_node_edit_form_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
    'style' => 'naked',
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '7da4c079-23be-3d34-7157-4ddcccd34c47';
  $display->content['new-7da4c079-23be-3d34-7157-4ddcccd34c47'] = $pane;
  $display->panels['middle'][1] = 'new-7da4c079-23be-3d34-7157-4ddcccd34c47';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-fcca0d13-8819-8224-7933-2e3394760a77';
  $mini->display = $display;
  $export['taxonomy_vocabularies'] = $mini;

  return $export;
}
