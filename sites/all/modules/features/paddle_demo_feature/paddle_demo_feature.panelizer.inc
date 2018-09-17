<?php
/**
 * @file
 * paddle_demo_feature.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_demo_feature_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'demo';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:demo:default';
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
  $display->layout = 'paddle_3_col_c';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
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
  $display->uuid = 'c53037e6-a13f-4011-9f08-0a26e109feb4';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:demo:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-8dbe23fa-563d-412d-b8cc-9838fd57e264';
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
    'override_title' => 1,
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
  $pane->uuid = '8dbe23fa-563d-412d-b8cc-9838fd57e264';
  $display->content['new-8dbe23fa-563d-412d-b8cc-9838fd57e264'] = $pane;
  $display->panels['1_a'][0] = 'new-8dbe23fa-563d-412d-b8cc-9838fd57e264';
  $pane = new stdClass();
  $pane->pid = 'new-d69d060b-7528-4fac-b8c8-b5b10a970b41';
  $pane->panel = '2_a';
  $pane->type = 'content_region';
  $pane->subtype = 'content_region';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'all_pages',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = 'd69d060b-7528-4fac-b8c8-b5b10a970b41';
  $display->content['new-d69d060b-7528-4fac-b8c8-b5b10a970b41'] = $pane;
  $display->panels['2_a'][0] = 'new-d69d060b-7528-4fac-b8c8-b5b10a970b41';
  $pane = new stdClass();
  $pane->pid = 'new-96120f8b-8446-4abd-a1cb-9a06de8ffaa7';
  $pane->panel = '3_b';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_demo_info_block_1';
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
    'override_title' => 1,
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
  $pane->uuid = '96120f8b-8446-4abd-a1cb-9a06de8ffaa7';
  $display->content['new-96120f8b-8446-4abd-a1cb-9a06de8ffaa7'] = $pane;
  $display->panels['3_b'][0] = 'new-96120f8b-8446-4abd-a1cb-9a06de8ffaa7';
  $pane = new stdClass();
  $pane->pid = 'new-320150d9-0382-4372-9518-ad8005c41c22';
  $pane->panel = '3_b';
  $pane->type = 'content_region';
  $pane->subtype = 'content_region';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'all_pages',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '320150d9-0382-4372-9518-ad8005c41c22';
  $display->content['new-320150d9-0382-4372-9518-ad8005c41c22'] = $pane;
  $display->panels['3_b'][1] = 'new-320150d9-0382-4372-9518-ad8005c41c22';
  $pane = new stdClass();
  $pane->pid = 'new-685fdcf7-1b6b-45d8-81de-85d23ab0009e';
  $pane->panel = '4_b';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_demo_info_block_2';
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
    'override_title' => 1,
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
  $pane->uuid = '685fdcf7-1b6b-45d8-81de-85d23ab0009e';
  $display->content['new-685fdcf7-1b6b-45d8-81de-85d23ab0009e'] = $pane;
  $display->panels['4_b'][0] = 'new-685fdcf7-1b6b-45d8-81de-85d23ab0009e';
  $pane = new stdClass();
  $pane->pid = 'new-ae64ae37-4a14-4d00-9371-b65e2450de3f';
  $pane->panel = '5_b';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_demo_info_block_3';
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
    'override_title' => 1,
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
  $pane->uuid = 'ae64ae37-4a14-4d00-9371-b65e2450de3f';
  $display->content['new-ae64ae37-4a14-4d00-9371-b65e2450de3f'] = $pane;
  $display->panels['5_b'][0] = 'new-ae64ae37-4a14-4d00-9371-b65e2450de3f';
  $pane = new stdClass();
  $pane->pid = 'new-10f9802c-61d5-4b11-905b-7484258fbbfd';
  $pane->panel = 'top';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_demo_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'image',
    'delta_limit' => 0,
    'delta_offset' => '0',
    'delta_reversed' => FALSE,
    'formatter_settings' => array(
      'image_style' => 'large',
      'image_link' => '',
    ),
    'context' => 'panelizer',
    'override_title' => 1,
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
  $pane->uuid = '10f9802c-61d5-4b11-905b-7484258fbbfd';
  $display->content['new-10f9802c-61d5-4b11-905b-7484258fbbfd'] = $pane;
  $display->panels['top'][0] = 'new-10f9802c-61d5-4b11-905b-7484258fbbfd';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-8dbe23fa-563d-412d-b8cc-9838fd57e264';
  $panelizer->display = $display;
  $export['node:demo:default'] = $panelizer;

  return $export;
}
