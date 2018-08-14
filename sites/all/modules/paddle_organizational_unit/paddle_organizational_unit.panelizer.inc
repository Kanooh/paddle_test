<?php
/**
 * @file
 * paddle_organizational_unit.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_organizational_unit_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'organizational_unit';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:organizational_unit:default';
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
  $display->layout = 'paddle_celebi';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'full_a' => NULL,
      'nested_7_b' => NULL,
      'nested_5_c' => NULL,
      'bottom' => NULL,
      'nested_6_e' => NULL,
      'nested_6_f' => NULL,
      'nested_4_g' => NULL,
      'nested_4_h' => NULL,
      'nested_4_i' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '238ed114-c194-11e3-b4b6-b6ee55aeb395';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:organizational_unit:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-e3020176-9567-486c-a944-3b458de52b74';
  $pane->panel = 'full_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_featured_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'paddle_organizational_unit_logo',
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
      'full_a' => 'full_a',
    ),
  );
  $pane->uuid = 'e3020176-9567-486c-a944-3b458de52b74';
  $display->content['new-e3020176-9567-486c-a944-3b458de52b74'] = $pane;
  $display->panels['full_a'][0] = 'new-e3020176-9567-486c-a944-3b458de52b74';
  $pane = new stdClass();
  $pane->pid = 'new-ca53446f-a6f4-4a0e-b469-262c215b873b';
  $pane->panel = 'full_a';
  $pane->type = 'organizational_unit_info';
  $pane->subtype = 'organizational_unit_info';
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
      'full_a' => 'full_a',
    ),
  );
  $pane->uuid = 'ca53446f-a6f4-4a0e-b469-262c215b873b';
  $display->content['new-ca53446f-a6f4-4a0e-b469-262c215b873b'] = $pane;
  $display->panels['full_a'][1] = 'new-ca53446f-a6f4-4a0e-b469-262c215b873b';
  $pane = new stdClass();
  $pane->pid = 'new-3f99812e-c194-11e3-b4b6-b6ee55aeb395';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'organizational_unit',
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
  $pane->uuid = '3f99812e-c194-11e3-b4b6-b6ee55aeb395';
  $display->content['new-3f99812e-c194-11e3-b4b6-b6ee55aeb395'] = $pane;
  $display->panels['bottom'][0] = 'new-3f99812e-c194-11e3-b4b6-b6ee55aeb395';
  $pane = new stdClass();
  $pane->pid = 'new-62279c94-115f-42ba-9c6e-c2af9ba18a09';
  $pane->panel = 'nested_5_c';
  $pane->type = 'contact_information';
  $pane->subtype = 'contact_information';
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
      'nested_5_c' => 'nested_5_c',
    ),
  );
  $pane->uuid = '62279c94-115f-42ba-9c6e-c2af9ba18a09';
  $display->content['new-62279c94-115f-42ba-9c6e-c2af9ba18a09'] = $pane;
  $display->panels['nested_5_c'][0] = 'new-62279c94-115f-42ba-9c6e-c2af9ba18a09';
  $pane = new stdClass();
  $pane->pid = 'new-3494ba3a-7a8b-4928-a23a-1cedb3d61498';
  $pane->panel = 'nested_7_b';
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
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'nested_7_b' => 'nested_7_b',
    ),
  );
  $pane->uuid = '3494ba3a-7a8b-4928-a23a-1cedb3d61498';
  $display->content['new-3494ba3a-7a8b-4928-a23a-1cedb3d61498'] = $pane;
  $display->panels['nested_7_b'][0] = 'new-3494ba3a-7a8b-4928-a23a-1cedb3d61498';
  $pane = new stdClass();
  $pane->pid = 'new-7c6df9e0-c194-11e3-b4b6-b6ee55aeb395';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'organizational_unit',
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
  $pane->uuid = '7c6df9e0-c194-11e3-b4b6-b6ee55aeb395';
  $display->content['new-7c6df9e0-c194-11e3-b4b6-b6ee55aeb395'] = $pane;
  $display->panels['right'][0] = 'new-7c6df9e0-c194-11e3-b4b6-b6ee55aeb395';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:organizational_unit:default'] = $panelizer;

  return $export;
}
