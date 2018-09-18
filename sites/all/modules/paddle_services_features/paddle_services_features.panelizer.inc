<?php
/**
 * @file
 * paddle_services_features.panelizer.inc
 */

/**
 * Implements hook_panelizer_defaults().
 */
function paddle_services_features_panelizer_defaults() {
  $export = array();

  $panelizer = new stdClass();
  $panelizer->disabled = FALSE; /* Edit this to true to make a default panelizer disabled initially */
  $panelizer->api_version = 1;
  $panelizer->title = 'Default';
  $panelizer->panelizer_type = 'node';
  $panelizer->panelizer_key = 'services';
  $panelizer->access = array();
  $panelizer->view_mode = 'page_manager';
  $panelizer->name = 'node:services:default';
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
  $display->uuid = '57357f82-e2c9-4b31-be5f-16c8e18d42ad';
  $display->storage_type = 'panelizer_default';
  $display->storage_id = 'node:services:default';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-269e3a38-4d20-47e7-91ca-59c659d1e28d';
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
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '269e3a38-4d20-47e7-91ca-59c659d1e28d';
  $display->content['new-269e3a38-4d20-47e7-91ca-59c659d1e28d'] = $pane;
  $display->panels['1_a'][0] = 'new-269e3a38-4d20-47e7-91ca-59c659d1e28d';
  $pane = new stdClass();
  $pane->pid = 'new-35786e69-e90c-4463-be23-f99af4cd07c6';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_tags';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->locks = array();
  $pane->uuid = '35786e69-e90c-4463-be23-f99af4cd07c6';
  $display->content['new-35786e69-e90c-4463-be23-f99af4cd07c6'] = $pane;
  $display->panels['1_a'][1] = 'new-35786e69-e90c-4463-be23-f99af4cd07c6';
  $pane = new stdClass();
  $pane->pid = 'new-d88b9a4b-0405-4341-b95b-d98cb83f2482';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_general_tags';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->locks = array();
  $pane->uuid = 'd88b9a4b-0405-4341-b95b-d98cb83f2482';
  $display->content['new-d88b9a4b-0405-4341-b95b-d98cb83f2482'] = $pane;
  $display->panels['1_a'][2] = 'new-d88b9a4b-0405-4341-b95b-d98cb83f2482';
  $pane = new stdClass();
  $pane->pid = 'new-40d97014-3fa9-4ace-bf22-c76cbe5dff25';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_seo_title';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 3;
  $pane->locks = array();
  $pane->uuid = '40d97014-3fa9-4ace-bf22-c76cbe5dff25';
  $display->content['new-40d97014-3fa9-4ace-bf22-c76cbe5dff25'] = $pane;
  $display->panels['1_a'][3] = 'new-40d97014-3fa9-4ace-bf22-c76cbe5dff25';
  $pane = new stdClass();
  $pane->pid = 'new-4f08f9d4-661f-4b3e-9967-1393c48f3f40';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_seo_description';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 4;
  $pane->locks = array();
  $pane->uuid = '4f08f9d4-661f-4b3e-9967-1393c48f3f40';
  $display->content['new-4f08f9d4-661f-4b3e-9967-1393c48f3f40'] = $pane;
  $display->panels['1_a'][4] = 'new-4f08f9d4-661f-4b3e-9967-1393c48f3f40';
  $pane = new stdClass();
  $pane->pid = 'new-0e277e51-467a-4239-96e4-305128aacb94';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_show_next_level';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 5;
  $pane->locks = array();
  $pane->uuid = '0e277e51-467a-4239-96e4-305128aacb94';
  $display->content['new-0e277e51-467a-4239-96e4-305128aacb94'] = $pane;
  $display->panels['1_a'][5] = 'new-0e277e51-467a-4239-96e4-305128aacb94';
  $pane = new stdClass();
  $pane->pid = 'new-15d38f3a-30a8-44f4-88c3-da229dedde8d';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_show_breadcrumb';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 6;
  $pane->locks = array();
  $pane->uuid = '15d38f3a-30a8-44f4-88c3-da229dedde8d';
  $display->content['new-15d38f3a-30a8-44f4-88c3-da229dedde8d'] = $pane;
  $display->panels['1_a'][6] = 'new-15d38f3a-30a8-44f4-88c3-da229dedde8d';
  $pane = new stdClass();
  $pane->pid = 'new-d9b3c85f-1eab-4e19-8dea-a90e5030634f';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_page_responsible_author';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 7;
  $pane->locks = array();
  $pane->uuid = 'd9b3c85f-1eab-4e19-8dea-a90e5030634f';
  $display->content['new-d9b3c85f-1eab-4e19-8dea-a90e5030634f'] = $pane;
  $display->panels['1_a'][7] = 'new-d9b3c85f-1eab-4e19-8dea-a90e5030634f';
  $pane = new stdClass();
  $pane->pid = 'new-582a797a-c562-4011-9ab5-67af94469357';
  $pane->panel = '1_a';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_featured_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'hidden',
    'formatter' => 'hidden',
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
  $pane->position = 8;
  $pane->locks = array();
  $pane->uuid = '582a797a-c562-4011-9ab5-67af94469357';
  $display->content['new-582a797a-c562-4011-9ab5-67af94469357'] = $pane;
  $display->panels['1_a'][8] = 'new-582a797a-c562-4011-9ab5-67af94469357';
  $pane = new stdClass();
  $pane->pid = 'new-d030cf73-47cf-4282-b5ab-efd341ffe240';
  $pane->panel = '1_a';
  $pane->type = 'node_links';
  $pane->subtype = 'node_links';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'override_title' => FALSE,
    'override_title_text' => '',
    'build_mode' => 'page_manager',
    'identifier' => '',
    'link' => TRUE,
    'context' => 'panelizer',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_class' => 'link-wrapper',
  );
  $pane->extras = array();
  $pane->position = 9;
  $pane->locks = array();
  $pane->uuid = 'd030cf73-47cf-4282-b5ab-efd341ffe240';
  $display->content['new-d030cf73-47cf-4282-b5ab-efd341ffe240'] = $pane;
  $display->panels['1_a'][9] = 'new-d030cf73-47cf-4282-b5ab-efd341ffe240';
  $pane = new stdClass();
  $pane->pid = 'new-e68e2c2a-ef83-48c8-8d18-6239b8546a6c';
  $pane->panel = '1_a';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:body';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
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
  $pane->position = 10;
  $pane->locks = array();
  $pane->uuid = 'e68e2c2a-ef83-48c8-8d18-6239b8546a6c';
  $display->content['new-e68e2c2a-ef83-48c8-8d18-6239b8546a6c'] = $pane;
  $display->panels['1_a'][10] = 'new-e68e2c2a-ef83-48c8-8d18-6239b8546a6c';
  $pane = new stdClass();
  $pane->pid = 'new-c75e74c4-a148-4110-9f20-98621fe3085f';
  $pane->panel = '3_b';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_services_text1';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
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
  $pane->uuid = 'c75e74c4-a148-4110-9f20-98621fe3085f';
  $display->content['new-c75e74c4-a148-4110-9f20-98621fe3085f'] = $pane;
  $display->panels['3_b'][0] = 'new-c75e74c4-a148-4110-9f20-98621fe3085f';
  $pane = new stdClass();
  $pane->pid = 'new-e3e607ec-34b5-40a2-8f18-f898f6aae565';
  $pane->panel = '4_b';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_services_text2';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
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
  $pane->uuid = 'e3e607ec-34b5-40a2-8f18-f898f6aae565';
  $display->content['new-e3e607ec-34b5-40a2-8f18-f898f6aae565'] = $pane;
  $display->panels['4_b'][0] = 'new-e3e607ec-34b5-40a2-8f18-f898f6aae565';
  $pane = new stdClass();
  $pane->pid = 'new-a3d6ebb9-76c2-4bb5-89f7-b8d674a06e68';
  $pane->panel = '5_b';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_services_text3';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
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
  $pane->uuid = 'a3d6ebb9-76c2-4bb5-89f7-b8d674a06e68';
  $display->content['new-a3d6ebb9-76c2-4bb5-89f7-b8d674a06e68'] = $pane;
  $display->panels['5_b'][0] = 'new-a3d6ebb9-76c2-4bb5-89f7-b8d674a06e68';
  $pane = new stdClass();
  $pane->pid = 'new-dfaf4fca-c9ca-43a3-aa0a-cf82b35bf47b';
  $pane->panel = 'top';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_services_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
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
  $pane->uuid = 'dfaf4fca-c9ca-43a3-aa0a-cf82b35bf47b';
  $display->content['new-dfaf4fca-c9ca-43a3-aa0a-cf82b35bf47b'] = $pane;
  $display->panels['top'][0] = 'new-dfaf4fca-c9ca-43a3-aa0a-cf82b35bf47b';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-269e3a38-4d20-47e7-91ca-59c659d1e28d';
  $panelizer->display = $display;
  $export['node:services:default'] = $panelizer;

  return $export;
}
