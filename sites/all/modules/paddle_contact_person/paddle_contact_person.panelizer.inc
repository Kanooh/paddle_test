<?php
/**
 * @file
 * paddle_contact_person.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_contact_person_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'contact_person';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:contact_person:default';
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
      'nested_top' => NULL,
      'nested_left' => NULL,
      'nested_right' => NULL,
      'right' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '9f35e625-6cd6-4757-95ff-37b60585fc83';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:contact_person:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-16647e5b-d9da-4cbd-bb3b-967a729b6a1b';
  $pane->panel = 'bottom';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'contact_person',
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
  $pane->uuid = '16647e5b-d9da-4cbd-bb3b-967a729b6a1b';
  $display->content['new-16647e5b-d9da-4cbd-bb3b-967a729b6a1b'] = $pane;
  $display->panels['bottom'][0] = 'new-16647e5b-d9da-4cbd-bb3b-967a729b6a1b';
  $pane = new stdClass();
  $pane->pid = 'new-4216b49f-cab9-4401-bbc3-9f3b948289fd';
  $pane->panel = 'nested_left';
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
      'bottom' => 'nested_left',
    ),
  );
  $pane->uuid = '4216b49f-cab9-4401-bbc3-9f3b948289fd';
  $display->content['new-4216b49f-cab9-4401-bbc3-9f3b948289fd'] = $pane;
  $display->panels['nested_left'][0] = 'new-4216b49f-cab9-4401-bbc3-9f3b948289fd';
  $pane = new stdClass();
  $pane->pid = 'new-4d76f1f8-8617-4d1d-9853-116ff8c0a54f';
  $pane->panel = 'nested_right';
  $pane->type = 'contact_person_meta';
  $pane->subtype = 'contact_person_meta';
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
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'bottom' => 'nested_right',
    ),
  );
  $pane->uuid = '4d76f1f8-8617-4d1d-9853-116ff8c0a54f';
  $display->content['new-4d76f1f8-8617-4d1d-9853-116ff8c0a54f'] = $pane;
  $display->panels['nested_right'][0] = 'new-4d76f1f8-8617-4d1d-9853-116ff8c0a54f';
  $pane = new stdClass();
  $pane->pid = 'new-a96fc1e0-ac13-44ef-ab0f-4d6ebd243c23';
  $pane->panel = 'nested_top';
  $pane->type = 'contact_person_info';
  $pane->subtype = 'contact_person_info';
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
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'bottom' => 'nested_top',
    ),
  );
  $pane->uuid = 'a96fc1e0-ac13-44ef-ab0f-4d6ebd243c23';
  $display->content['new-a96fc1e0-ac13-44ef-ab0f-4d6ebd243c23'] = $pane;
  $display->panels['nested_top'][0] = 'new-a96fc1e0-ac13-44ef-ab0f-4d6ebd243c23';
  $pane = new stdClass();
  $pane->pid = 'new-0e96f03e-d6ec-4938-9415-388a0b453d1d';
  $pane->panel = 'right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'contact_person',
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
  $pane->uuid = '0e96f03e-d6ec-4938-9415-388a0b453d1d';
  $display->content['new-0e96f03e-d6ec-4938-9415-388a0b453d1d'] = $pane;
  $display->panels['right'][0] = 'new-0e96f03e-d6ec-4938-9415-388a0b453d1d';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:contact_person:default'] = $panelizer;

  return $export;
}
