<?php
/**
 * @file
 * paddle_company_guide.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_company_guide_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'company_page';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:company_page:default';
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
  $display->uuid = '9db12c62-97cc-4a54-a0ab-a34fba2a4085';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:company_page:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-f0fa725c-796b-4bdd-9889-9919048b2ce4';
  $pane->panel = 'left';
  $pane->type = 'basic_info';
  $pane->subtype = 'basic_info';
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
  $pane->uuid = 'f0fa725c-796b-4bdd-9889-9919048b2ce4';
  $display->content['new-f0fa725c-796b-4bdd-9889-9919048b2ce4'] = $pane;
  $display->panels['left'][0] = 'new-f0fa725c-796b-4bdd-9889-9919048b2ce4';
  $pane = new stdClass();
  $pane->pid = 'new-e8c8e5a1-984b-436f-adff-18008ffedd82';
  $pane->panel = 'left';
  $pane->type = 'opening_hours_cg';
  $pane->subtype = 'opening_hours_cg';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array();
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
  $pane->uuid = 'e8c8e5a1-984b-436f-adff-18008ffedd82';
  $display->content['new-e8c8e5a1-984b-436f-adff-18008ffedd82'] = $pane;
  $display->panels['left'][1] = 'new-e8c8e5a1-984b-436f-adff-18008ffedd82';
  $pane = new stdClass();
  $pane->pid = 'new-43acea24-5b40-42ea-9d80-2d9879f3523b';
  $pane->panel = 'left';
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
  $pane->position = 2;
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'left' => 'left',
    ),
  );
  $pane->uuid = '43acea24-5b40-42ea-9d80-2d9879f3523b';
  $display->content['new-43acea24-5b40-42ea-9d80-2d9879f3523b'] = $pane;
  $display->panels['left'][2] = 'new-43acea24-5b40-42ea-9d80-2d9879f3523b';
  $pane = new stdClass();
  $pane->pid = 'new-0f4b4f49-a118-48e3-89b7-12c1ecd6b284';
  $pane->panel = 'right';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_company_page_main_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'paddle_company_guide_main_image',
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
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'right' => 'right',
    ),
  );
  $pane->uuid = '0f4b4f49-a118-48e3-89b7-12c1ecd6b284';
  $display->content['new-0f4b4f49-a118-48e3-89b7-12c1ecd6b284'] = $pane;
  $display->panels['right'][0] = 'new-0f4b4f49-a118-48e3-89b7-12c1ecd6b284';
  $pane = new stdClass();
  $pane->pid = 'new-30910c69-4e12-4763-bc4e-f907874764bc';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'company_page',
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
      'right' => 'right',
    ),
  );
  $pane->uuid = '30910c69-4e12-4763-bc4e-f907874764bc';
  $display->content['new-30910c69-4e12-4763-bc4e-f907874764bc'] = $pane;
  $display->panels['right'][1] = 'new-30910c69-4e12-4763-bc4e-f907874764bc';
  $pane = new stdClass();
  $pane->pid = 'new-90685619-31dc-42b0-99b7-de1d53a23945';
  $pane->panel = 'right';
  $pane->type = 'map';
  $pane->subtype = 'map';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array();
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 2;
  $pane->locks = array();
  $pane->uuid = '90685619-31dc-42b0-99b7-de1d53a23945';
  $display->content['new-90685619-31dc-42b0-99b7-de1d53a23945'] = $pane;
  $display->panels['right'][2] = 'new-90685619-31dc-42b0-99b7-de1d53a23945';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = 'new-43acea24-5b40-42ea-9d80-2d9879f3523b';
  $panelizer->display = $display;
  $export['node:company_page:default'] = $panelizer;

  return $export;
}
