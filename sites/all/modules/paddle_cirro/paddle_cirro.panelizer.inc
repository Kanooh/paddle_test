<?php
/**
 * @file
 * paddle_cirro.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_cirro_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_cirro_page';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:paddle_cirro_page:default';
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
      'right' => NULL,
      'left' => NULL,
      'bottom' => NULL,
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
  $display->uuid = '6e5bbd86-cc26-4b0a-ae5f-59b0ce362435';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:paddle_cirro_page:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-6e14c293-8002-479d-8542-94c8e82df37a';
  $pane->panel = '1_a';
  $pane->type = 'cirro_terms';
  $pane->subtype = 'cirro_terms';
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
      '1_a' => '1_a',
    ),
  );
  $pane->uuid = '6e14c293-8002-479d-8542-94c8e82df37a';
  $display->content['new-6e14c293-8002-479d-8542-94c8e82df37a'] = $pane;
  $display->panels['1_a'][0] = 'new-6e14c293-8002-479d-8542-94c8e82df37a';
  $pane = new stdClass();
  $pane->pid = 'new-8a88ae82-2979-4413-8fa2-8e2cb4c651b2';
  $pane->panel = '1_a';
  $pane->type = 'cirro_materials';
  $pane->subtype = 'cirro_materials';
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
      '1_a' => '1_a',
    ),
  );
  $pane->uuid = '8a88ae82-2979-4413-8fa2-8e2cb4c651b2';
  $display->content['new-8a88ae82-2979-4413-8fa2-8e2cb4c651b2'] = $pane;
  $display->panels['1_a'][1] = 'new-8a88ae82-2979-4413-8fa2-8e2cb4c651b2';
  $pane = new stdClass();
  $pane->pid = 'new-1e187eb5-683a-4d51-8714-1d2285a87a1f';
  $pane->panel = '2_a';
  $pane->type = 'cirro_contact_info';
  $pane->subtype = 'cirro_contact_info';
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
      'right' => 'right',
    ),
  );
  $pane->uuid = '1e187eb5-683a-4d51-8714-1d2285a87a1f';
  $display->content['new-1e187eb5-683a-4d51-8714-1d2285a87a1f'] = $pane;
  $display->panels['2_a'][0] = 'new-1e187eb5-683a-4d51-8714-1d2285a87a1f';
  $pane = new stdClass();
  $pane->pid = 'new-90b8c87a-f20b-41ec-82fc-57be67a28d23';
  $pane->panel = '3_b';
  $pane->type = 'cirro_related_entities';
  $pane->subtype = 'cirro_related_entities';
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
      '3_b' => '3_b',
    ),
  );
  $pane->uuid = '90b8c87a-f20b-41ec-82fc-57be67a28d23';
  $display->content['new-90b8c87a-f20b-41ec-82fc-57be67a28d23'] = $pane;
  $display->panels['3_b'][0] = 'new-90b8c87a-f20b-41ec-82fc-57be67a28d23';
  $pane = new stdClass();
  $pane->pid = 'new-418ee594-7030-4b76-ab0d-c9515c71e90b';
  $pane->panel = '4_b';
  $pane->type = 'cirro_parent_entities';
  $pane->subtype = 'cirro_parent_entities';
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
      '4_b' => '4_b',
    ),
  );
  $pane->uuid = '418ee594-7030-4b76-ab0d-c9515c71e90b';
  $display->content['new-418ee594-7030-4b76-ab0d-c9515c71e90b'] = $pane;
  $display->panels['4_b'][0] = 'new-418ee594-7030-4b76-ab0d-c9515c71e90b';
  $pane = new stdClass();
  $pane->pid = 'new-52387c75-151c-4249-a04f-cd94909c0bb0';
  $pane->panel = 'top';
  $pane->type = 'cirro_description';
  $pane->subtype = 'cirro_description';
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
      'top' => 'top',
    ),
  );
  $pane->uuid = '52387c75-151c-4249-a04f-cd94909c0bb0';
  $display->content['new-52387c75-151c-4249-a04f-cd94909c0bb0'] = $pane;
  $display->panels['top'][0] = 'new-52387c75-151c-4249-a04f-cd94909c0bb0';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $panelizer->display = $display;
  $export['node:paddle_cirro_page:default'] = $panelizer;

  return $export;
}
