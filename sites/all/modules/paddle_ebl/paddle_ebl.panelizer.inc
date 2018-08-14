<?php
/**
 * @file
 * paddle_ebl.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_ebl_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'paddle_ebl_page';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:paddle_ebl_page:default';
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
  $display->layout = 'paddle_2_cols_3_cols_d';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'right' => NULL,
      'left' => NULL,
      'bottom' => NULL,
      'row_1_left' => NULL,
      'row_1_right' => NULL,
      'row_2_left' => NULL,
      'row_2_middle' => NULL,
      'row_2_right' => NULL,
      'bottom_row' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%node:title';
  $display->uuid = '28dea263-604a-41ba-9cb6-ba5da0695506';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:paddle_ebl_page:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-806ea3d4-ef93-4ae7-9116-baa466512a29';
  $pane->panel = 'bottom_row';
  $pane->type = 'ebl_cover';
  $pane->subtype = 'ebl_cover';
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
  $pane->locks = array();
  $pane->uuid = '806ea3d4-ef93-4ae7-9116-baa466512a29';
  $display->content['new-806ea3d4-ef93-4ae7-9116-baa466512a29'] = $pane;
  $display->panels['bottom_row'][0] = 'new-806ea3d4-ef93-4ae7-9116-baa466512a29';
  $pane = new stdClass();
  $pane->pid = 'new-28a29d9c-73da-48fb-8fca-0bcc3267e0ca';
  $pane->panel = 'bottom_row';
  $pane->type = 'ebl_metadata';
  $pane->subtype = 'ebl_metadata';
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
      'bottom_row' => 'bottom_row',
    ),
  );
  $pane->uuid = '28a29d9c-73da-48fb-8fca-0bcc3267e0ca';
  $display->content['new-28a29d9c-73da-48fb-8fca-0bcc3267e0ca'] = $pane;
  $display->panels['bottom_row'][1] = 'new-28a29d9c-73da-48fb-8fca-0bcc3267e0ca';
  $pane = new stdClass();
  $pane->pid = 'new-f88b285a-d802-424e-8d5e-0c3f17ffe0e7';
  $pane->panel = 'bottom_row';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'bottom',
    'type' => 'paddle_ebl_page',
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
      'bottom' => 'bottom_row',
    ),
  );
  $pane->uuid = 'f88b285a-d802-424e-8d5e-0c3f17ffe0e7';
  $display->content['new-f88b285a-d802-424e-8d5e-0c3f17ffe0e7'] = $pane;
  $display->panels['bottom_row'][2] = 'new-f88b285a-d802-424e-8d5e-0c3f17ffe0e7';
  $pane = new stdClass();
  $pane->pid = 'new-9f82f61a-41a2-463c-9205-b0b9750e4138';
  $pane->panel = 'row_1_left';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_ebl_subtitle';
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
      'row_1_left' => 'row_1_left',
    ),
  );
  $pane->uuid = '9f82f61a-41a2-463c-9205-b0b9750e4138';
  $display->content['new-9f82f61a-41a2-463c-9205-b0b9750e4138'] = $pane;
  $display->panels['row_1_left'][0] = 'new-9f82f61a-41a2-463c-9205-b0b9750e4138';
  $pane = new stdClass();
  $pane->pid = 'new-ddeb4a01-f749-40dd-9fdf-ddc045c2c734';
  $pane->panel = 'row_1_left';
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
  $pane->position = 1;
  $pane->locks = array(
    'type' => 'immovable',
    'regions' => array(
      'row_1_left' => 'row_1_left',
    ),
  );
  $pane->uuid = 'ddeb4a01-f749-40dd-9fdf-ddc045c2c734';
  $display->content['new-ddeb4a01-f749-40dd-9fdf-ddc045c2c734'] = $pane;
  $display->panels['row_1_left'][1] = 'new-ddeb4a01-f749-40dd-9fdf-ddc045c2c734';
  $pane = new stdClass();
  $pane->pid = 'new-13b833c4-5f04-4b47-94b6-d96cb047fe65';
  $pane->panel = 'row_1_right';
  $pane->type = 'ebl_links';
  $pane->subtype = 'ebl_links';
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
      'row_1_right' => 'row_1_right',
    ),
  );
  $pane->uuid = '13b833c4-5f04-4b47-94b6-d96cb047fe65';
  $display->content['new-13b833c4-5f04-4b47-94b6-d96cb047fe65'] = $pane;
  $display->panels['row_1_right'][0] = 'new-13b833c4-5f04-4b47-94b6-d96cb047fe65';
  $pane = new stdClass();
  $pane->pid = 'new-4e7da1e8-cc52-4bf4-ac19-078eeae12b81';
  $pane->panel = 'row_1_right';
  $pane->type = 'ebl_urls';
  $pane->subtype = 'ebl_urls';
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
      'row_1_right' => 'row_1_right',
    ),
  );
  $pane->uuid = '4e7da1e8-cc52-4bf4-ac19-078eeae12b81';
  $display->content['new-4e7da1e8-cc52-4bf4-ac19-078eeae12b81'] = $pane;
  $display->panels['row_1_right'][1] = 'new-4e7da1e8-cc52-4bf4-ac19-078eeae12b81';
  $pane = new stdClass();
  $pane->pid = 'new-30910c69-4e12-4763-bc4e-f907874764bb';
  $pane->panel = 'row_1_right';
  $pane->type = 'content_region';
  $pane->subtype = 'inherit';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'region' => 'right',
    'type' => 'paddle_ebl_page',
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
      'right' => 'row_1_right',
    ),
  );
  $pane->uuid = '30910c69-4e12-4763-bc4e-f907874764bb';
  $display->content['new-30910c69-4e12-4763-bc4e-f907874764bb'] = $pane;
  $display->panels['row_1_right'][2] = 'new-30910c69-4e12-4763-bc4e-f907874764bb';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-ddeb4a01-f749-40dd-9fdf-ddc045c2c734';
  $panelizer->display = $display;
  $export['node:paddle_ebl_page:default'] = $panelizer;

  return $export;
}
