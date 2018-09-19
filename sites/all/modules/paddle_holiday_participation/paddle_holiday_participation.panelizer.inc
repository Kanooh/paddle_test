<?php
/**
 * @file
 * paddle_holiday_participation.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_holiday_participation_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'offer';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:offer:default';
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
  $display->layout = 'paddle_2_col_9_3_d';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'right' => NULL,
      'left' => NULL,
      'bottom' => NULL,
      'nested_top' => NULL,
      'nested_left' => NULL,
      'nested_right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = 'ca3a0478-06f7-4c69-bbed-99b5b8f486b4';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:offer:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-22b14eae-6d58-45c8-a6f0-e6b6b5a615e9';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'offer',
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
  $pane->uuid = '22b14eae-6d58-45c8-a6f0-e6b6b5a615e9';
  $display->content['new-22b14eae-6d58-45c8-a6f0-e6b6b5a615e9'] = $pane;
  $display->panels['bottom'][0] = 'new-22b14eae-6d58-45c8-a6f0-e6b6b5a615e9';
  $pane = new stdClass();
  $pane->pid = 'new-e554ceb7-8df8-4861-bbfd-92ccdec3447a';
  $pane->panel = 'bottom';
  $pane->type = 'offer_bottom_pane';
  $pane->subtype = 'offer_bottom_pane';
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
  $pane->uuid = 'e554ceb7-8df8-4861-bbfd-92ccdec3447a';
  $display->content['new-e554ceb7-8df8-4861-bbfd-92ccdec3447a'] = $pane;
  $display->panels['bottom'][1] = 'new-e554ceb7-8df8-4861-bbfd-92ccdec3447a';
  $pane = new stdClass();
  $pane->pid = 'new-0064ba84-c106-404d-b663-38bcca5f14c5';
  $pane->panel = 'nested_left';
  $pane->type = 'offer_image_pane';
  $pane->subtype = 'offer_image_pane';
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
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '0064ba84-c106-404d-b663-38bcca5f14c5';
  $display->content['new-0064ba84-c106-404d-b663-38bcca5f14c5'] = $pane;
  $display->panels['nested_left'][0] = 'new-0064ba84-c106-404d-b663-38bcca5f14c5';
  $pane = new stdClass();
  $pane->pid = 'new-96b83eb9-aadd-4f73-ac42-37a0aae5af40';
  $pane->panel = 'nested_left';
  $pane->type = 'offer_left_pane';
  $pane->subtype = 'offer_left_pane';
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
  $pane->uuid = '96b83eb9-aadd-4f73-ac42-37a0aae5af40';
  $display->content['new-96b83eb9-aadd-4f73-ac42-37a0aae5af40'] = $pane;
  $display->panels['nested_left'][1] = 'new-96b83eb9-aadd-4f73-ac42-37a0aae5af40';
  $pane = new stdClass();
  $pane->pid = 'new-584c8c57-32f4-4c04-b2d5-c0fbe9d2af46';
  $pane->panel = 'nested_right';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:body';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'paddle_landing_page_summary_or_trimmed_plain_text',
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
  $pane->uuid = '584c8c57-32f4-4c04-b2d5-c0fbe9d2af46';
  $display->content['new-584c8c57-32f4-4c04-b2d5-c0fbe9d2af46'] = $pane;
  $display->panels['nested_right'][0] = 'new-584c8c57-32f4-4c04-b2d5-c0fbe9d2af46';
  $pane = new stdClass();
  $pane->pid = 'new-d5127100-8e75-4317-b804-044d611355c3';
  $pane->panel = 'nested_right';
  $pane->type = 'offer_center_pane';
  $pane->subtype = 'offer_center_pane';
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
  $pane->uuid = 'd5127100-8e75-4317-b804-044d611355c3';
  $display->content['new-d5127100-8e75-4317-b804-044d611355c3'] = $pane;
  $display->panels['nested_right'][1] = 'new-d5127100-8e75-4317-b804-044d611355c3';
  $pane = new stdClass();
  $pane->pid = 'new-b7280f04-4873-4b3f-9a4b-5da23aaa7df2';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'offer',
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
  $pane->uuid = 'b7280f04-4873-4b3f-9a4b-5da23aaa7df2';
  $display->content['new-b7280f04-4873-4b3f-9a4b-5da23aaa7df2'] = $pane;
  $display->panels['right'][0] = 'new-b7280f04-4873-4b3f-9a4b-5da23aaa7df2';
  $pane = new stdClass();
  $pane->pid = 'new-a616eccf-2e98-4d34-a830-6bee4af0a740';
  $pane->panel = 'right';
  $pane->type = 'offer_right_pane';
  $pane->subtype = 'offer_right_pane';
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
  $pane->uuid = 'a616eccf-2e98-4d34-a830-6bee4af0a740';
  $display->content['new-a616eccf-2e98-4d34-a830-6bee4af0a740'] = $pane;
  $display->panels['right'][1] = 'new-a616eccf-2e98-4d34-a830-6bee4af0a740';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:offer:default'] = $panelizer;

  return $export;
}
