<?php
/**
 * @file
 * paddle_content_manager.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function paddle_content_manager_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'node_edit_panel_context';
  $handler->task = 'node_edit';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Node Create/Edit screen',
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
  $display->layout = 'twocol_stacked';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'left' => NULL,
      'right' => NULL,
      'top' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '922c1d2e-d102-d3e4-c14a-981fcbea7740';
  $display->storage_type = 'page_manager';
  $display->storage_id = 'node_edit_panel_context';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-436557ab-3176-6ae4-75c4-2e1204b234c3';
  $pane->panel = 'bottom';
  $pane->type = 'node_form_buttons';
  $pane->subtype = 'node_form_buttons';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
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
  $pane->locks = array();
  $pane->uuid = '436557ab-3176-6ae4-75c4-2e1204b234c3';
  $display->content['new-436557ab-3176-6ae4-75c4-2e1204b234c3'] = $pane;
  $display->panels['bottom'][0] = 'new-436557ab-3176-6ae4-75c4-2e1204b234c3';
  $pane = new stdClass();
  $pane->pid = 'new-4bc06284-f212-2f94-b18c-514e862e820e';
  $pane->panel = 'left';
  $pane->type = 'page_title';
  $pane->subtype = 'page_title';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'markup' => 'h1',
    'class' => 'title',
    'id' => 'page-title',
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
  $pane->locks = array();
  $pane->uuid = '4bc06284-f212-2f94-b18c-514e862e820e';
  $display->content['new-4bc06284-f212-2f94-b18c-514e862e820e'] = $pane;
  $display->panels['left'][0] = 'new-4bc06284-f212-2f94-b18c-514e862e820e';
  $pane = new stdClass();
  $pane->pid = 'new-bf80ecb1-38d0-1e24-01fa-e756328b3160';
  $pane->panel = 'left';
  $pane->type = 'node_form_title';
  $pane->subtype = 'node_form_title';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
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
  $pane->uuid = 'bf80ecb1-38d0-1e24-01fa-e756328b3160';
  $display->content['new-bf80ecb1-38d0-1e24-01fa-e756328b3160'] = $pane;
  $display->panels['left'][1] = 'new-bf80ecb1-38d0-1e24-01fa-e756328b3160';
  $pane = new stdClass();
  $pane->pid = 'new-f14a7a22-a05e-ae44-c122-e71a20f44d45';
  $pane->panel = 'left';
  $pane->type = 'additional_fields';
  $pane->subtype = 'additional_fields';
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
  $pane->uuid = 'f14a7a22-a05e-ae44-c122-e71a20f44d45';
  $display->content['new-f14a7a22-a05e-ae44-c122-e71a20f44d45'] = $pane;
  $display->panels['left'][2] = 'new-f14a7a22-a05e-ae44-c122-e71a20f44d45';
  $pane = new stdClass();
  $pane->pid = 'new-762b4408-5f7b-6814-d98d-c398e6ea71cc';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:body';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 3;
  $pane->locks = array();
  $pane->uuid = '762b4408-5f7b-6814-d98d-c398e6ea71cc';
  $display->content['new-762b4408-5f7b-6814-d98d-c398e6ea71cc'] = $pane;
  $display->panels['left'][3] = 'new-762b4408-5f7b-6814-d98d-c398e6ea71cc';
  $pane = new stdClass();
  $pane->pid = 'new-bcec2608-deb8-49ee-86ec-8ea34723fcce';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_featured_image';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
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
  $pane->position = 4;
  $pane->locks = array();
  $pane->uuid = 'bcec2608-deb8-49ee-86ec-8ea34723fcce';
  $display->content['new-bcec2608-deb8-49ee-86ec-8ea34723fcce'] = $pane;
  $display->panels['left'][4] = 'new-bcec2608-deb8-49ee-86ec-8ea34723fcce';
  $pane = new stdClass();
  $pane->pid = 'new-f23903ea-452d-6de4-a5ef-fc95db473875';
  $pane->panel = 'left';
  $pane->type = 'panels_mini';
  $pane->subtype = 'taxonomy_vocabularies';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => array(
      0 => 'argument_node_edit_1',
    ),
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
    'css_class' => 'foldable',
  );
  $pane->extras = array();
  $pane->position = 5;
  $pane->locks = array();
  $pane->uuid = 'f23903ea-452d-6de4-a5ef-fc95db473875';
  $display->content['new-f23903ea-452d-6de4-a5ef-fc95db473875'] = $pane;
  $display->panels['left'][5] = 'new-f23903ea-452d-6de4-a5ef-fc95db473875';
  $pane = new stdClass();
  $pane->pid = 'new-768c922d-77f4-46b4-a830-ff41e7ad4ba8';
  $pane->panel = 'left';
  $pane->type = 'node_form_comment';
  $pane->subtype = 'node_form_comment';
  $pane->shown = TRUE;
  $pane->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'node_type_comment_status_hidden',
        'settings' => NULL,
        'context' => 'argument_node_edit_1',
        'not' => FALSE,
      ),
    ),
  );
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
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
  $pane->position = 6;
  $pane->locks = array();
  $pane->uuid = '768c922d-77f4-46b4-a830-ff41e7ad4ba8';
  $display->content['new-768c922d-77f4-46b4-a830-ff41e7ad4ba8'] = $pane;
  $display->panels['left'][6] = 'new-768c922d-77f4-46b4-a830-ff41e7ad4ba8';
  $pane = new stdClass();
  $pane->pid = 'new-41fb463f-3be9-453d-97c7-3a5e92fb8572';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_enable_rating';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 7;
  $pane->locks = array();
  $pane->uuid = '41fb463f-3be9-453d-97c7-3a5e92fb8572';
  $display->content['new-41fb463f-3be9-453d-97c7-3a5e92fb8572'] = $pane;
  $display->panels['left'][7] = 'new-41fb463f-3be9-453d-97c7-3a5e92fb8572';
  $pane = new stdClass();
  $pane->pid = 'new-7dd04322-6b27-a0d4-d1a9-7de2e71a2296';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_seo_title';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 8;
  $pane->locks = array();
  $pane->uuid = '7dd04322-6b27-a0d4-d1a9-7de2e71a2296';
  $display->content['new-7dd04322-6b27-a0d4-d1a9-7de2e71a2296'] = $pane;
  $display->panels['left'][8] = 'new-7dd04322-6b27-a0d4-d1a9-7de2e71a2296';
  $pane = new stdClass();
  $pane->pid = 'new-3e5caea1-f26b-4e84-1d3c-e2e9a6a65555';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_seo_description';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 9;
  $pane->locks = array();
  $pane->uuid = '3e5caea1-f26b-4e84-1d3c-e2e9a6a65555';
  $display->content['new-3e5caea1-f26b-4e84-1d3c-e2e9a6a65555'] = $pane;
  $display->panels['left'][9] = 'new-3e5caea1-f26b-4e84-1d3c-e2e9a6a65555';
  $pane = new stdClass();
  $pane->pid = 'new-5c396613-e261-9fd4-cd50-90b12668e552';
  $pane->panel = 'left';
  $pane->type = 'panels_mini';
  $pane->subtype = 'basic_options';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => array(
      0 => 'argument_node_edit_1',
    ),
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
  );
  $pane->extras = array();
  $pane->position = 10;
  $pane->locks = array();
  $pane->uuid = '5c396613-e261-9fd4-cd50-90b12668e552';
  $display->content['new-5c396613-e261-9fd4-cd50-90b12668e552'] = $pane;
  $display->panels['left'][10] = 'new-5c396613-e261-9fd4-cd50-90b12668e552';
  $pane = new stdClass();
  $pane->pid = 'new-b38f45cb-6a8b-4a14-a573-26ae1264ef32';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_show_breadcrumb';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 11;
  $pane->locks = array();
  $pane->uuid = 'b38f45cb-6a8b-4a14-a573-26ae1264ef32';
  $display->content['new-b38f45cb-6a8b-4a14-a573-26ae1264ef32'] = $pane;
  $display->panels['left'][11] = 'new-b38f45cb-6a8b-4a14-a573-26ae1264ef32';
  $pane = new stdClass();
  $pane->pid = 'new-e413940a-91df-6764-759a-54d765e5b4c8';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_paddle_show_next_level';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 12;
  $pane->locks = array();
  $pane->uuid = 'e413940a-91df-6764-759a-54d765e5b4c8';
  $display->content['new-e413940a-91df-6764-759a-54d765e5b4c8'] = $pane;
  $display->panels['left'][12] = 'new-e413940a-91df-6764-759a-54d765e5b4c8';
  $pane = new stdClass();
  $pane->pid = 'new-e6415cd8-59e7-1944-e9f4-74a759ec1baa';
  $pane->panel = 'left';
  $pane->type = 'scheduler_form_pane';
  $pane->subtype = 'scheduler_form_pane';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
  );
  $pane->extras = array();
  $pane->position = 13;
  $pane->locks = array();
  $pane->uuid = 'e6415cd8-59e7-1944-e9f4-74a759ec1baa';
  $display->content['new-e6415cd8-59e7-1944-e9f4-74a759ec1baa'] = $pane;
  $display->panels['left'][13] = 'new-e6415cd8-59e7-1944-e9f4-74a759ec1baa';
  $pane = new stdClass();
  $pane->pid = 'new-31c2fa0e-25de-45df-929e-4c438d81bbc1';
  $pane->panel = 'left';
  $pane->type = 'entity_form_field';
  $pane->subtype = 'node:field_page_responsible_author';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => '',
    'formatter' => '',
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 14;
  $pane->locks = array();
  $pane->uuid = '31c2fa0e-25de-45df-929e-4c438d81bbc1';
  $display->content['new-31c2fa0e-25de-45df-929e-4c438d81bbc1'] = $pane;
  $display->panels['left'][14] = 'new-31c2fa0e-25de-45df-929e-4c438d81bbc1';
  $pane = new stdClass();
  $pane->pid = 'new-f4a1d4d8-beb6-493f-a029-fed1e9bb82b7';
  $pane->panel = 'left';
  $pane->type = 'node_form_creation_date';
  $pane->subtype = 'node_form_creation_date';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 15;
  $pane->locks = array();
  $pane->uuid = 'f4a1d4d8-beb6-493f-a029-fed1e9bb82b7';
  $display->content['new-f4a1d4d8-beb6-493f-a029-fed1e9bb82b7'] = $pane;
  $display->panels['left'][15] = 'new-f4a1d4d8-beb6-493f-a029-fed1e9bb82b7';
  $pane = new stdClass();
  $pane->pid = 'new-8762f7ff-1095-4f74-c10c-f313c4ebe77a';
  $pane->panel = 'right';
  $pane->type = 'node_summary_metadata';
  $pane->subtype = 'node_summary_metadata';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'override_title' => 0,
    'override_title_text' => '',
    'context' => 'argument_node_edit_1',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => 'node-metadata',
    'css_class' => '',
  );
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '8762f7ff-1095-4f74-c10c-f313c4ebe77a';
  $display->content['new-8762f7ff-1095-4f74-c10c-f313c4ebe77a'] = $pane;
  $display->panels['right'][0] = 'new-8762f7ff-1095-4f74-c10c-f313c4ebe77a';
  $pane = new stdClass();
  $pane->pid = 'new-05eb8c02-8b87-d0a4-d144-60dcf7255e4b';
  $pane->panel = 'right';
  $pane->type = 'node_form_editorial_notes';
  $pane->subtype = 'node_form_editorial_notes';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
    'css_class' => 'editorial-notes',
  );
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '05eb8c02-8b87-d0a4-d144-60dcf7255e4b';
  $display->content['new-05eb8c02-8b87-d0a4-d144-60dcf7255e4b'] = $pane;
  $display->panels['right'][1] = 'new-05eb8c02-8b87-d0a4-d144-60dcf7255e4b';
  $pane = new stdClass();
  $pane->pid = 'new-1e090af9-4b28-9734-bd41-065c5c9cc895';
  $pane->panel = 'right';
  $pane->type = 'moderation_history_pane';
  $pane->subtype = 'moderation_history_pane';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_node_edit_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
  );
  $pane->extras = array();
  $pane->position = 2;
  $pane->locks = array();
  $pane->uuid = '1e090af9-4b28-9734-bd41-065c5c9cc895';
  $display->content['new-1e090af9-4b28-9734-bd41-065c5c9cc895'] = $pane;
  $display->panels['right'][2] = 'new-1e090af9-4b28-9734-bd41-065c5c9cc895';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['node_edit_panel_context'] = $handler;

  return $export;
}

/**
 * Implements hook_default_page_manager_pages().
 */
function paddle_content_manager_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'content';
  $page->task = 'page';
  $page->admin_title = 'Content';
  $page->admin_description = 'Here you can create content';
  $page->path = 'admin/content_manager/add';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'perm',
        'settings' => array(
          'perm' => 'manage paddle_content_manager',
        ),
        'context' => 'logged-in-user',
        'not' => FALSE,
      ),
      1 => array(
        'name' => 'add_content',
        'settings' => NULL,
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array(
    'type' => 'normal',
    'title' => 'Add content',
    'name' => 'management',
    'weight' => '0',
    'parent' => array(
      'type' => 'normal',
      'title' => '',
      'name' => 'management',
      'weight' => '0',
    ),
  );
  $page->arguments = array();
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_content_panel_context';
  $handler->task = 'page';
  $handler->subtask = 'content';
  $handler->handler = 'panel_context';
  $handler->weight = 1;
  $handler->conf = array(
    'title' => 'Content',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'plugins' => array(),
      'logic' => 'and',
    ),
  );
  $display = new panels_display();
  $display->layout = 'threecol_25_50_25';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'top' => NULL,
      'left' => NULL,
      'right' => NULL,
      'bottom' => NULL,
      'middle' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = 'b9b38d24-c270-78f4-61be-930ab82b9fa4';
  $display->storage_type = 'page_manager';
  $display->storage_id = 'page_content_panel_context';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-2ade387f-c63b-bf94-6503-382a6ef321c8';
  $pane->panel = 'left';
  $pane->type = 'node_add_content_type_selection';
  $pane->subtype = 'node_add_content_type_selection';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'override_title' => 0,
    'override_title_text' => '',
    'excluded_node_types' => array(
      'landing_page' => 'landing_page',
    ),
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $pane->uuid = '2ade387f-c63b-bf94-6503-382a6ef321c8';
  $display->content['new-2ade387f-c63b-bf94-6503-382a6ef321c8'] = $pane;
  $display->panels['left'][0] = 'new-2ade387f-c63b-bf94-6503-382a6ef321c8';
  $pane = new stdClass();
  $pane->pid = 'new-f6c95150-4462-8994-edda-e0c06f6fbeaa';
  $pane->panel = 'middle';
  $pane->type = 'add_new_landing_page';
  $pane->subtype = 'add_new_landing_page';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
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
  $pane->locks = array();
  $pane->uuid = 'f6c95150-4462-8994-edda-e0c06f6fbeaa';
  $display->content['new-f6c95150-4462-8994-edda-e0c06f6fbeaa'] = $pane;
  $display->panels['middle'][0] = 'new-f6c95150-4462-8994-edda-e0c06f6fbeaa';
  $pane = new stdClass();
  $pane->pid = 'new-537ed005-d997-4adb-b533-aad941b106f4';
  $pane->panel = 'right';
  $pane->type = 'create_atom_links_list';
  $pane->subtype = 'create_atom_links_list';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'excluded_atom_types' => array(
      'file' => 0,
      'image' => 0,
      'video' => 0,
    ),
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
  $pane->locks = array();
  $pane->uuid = '537ed005-d997-4adb-b533-aad941b106f4';
  $display->content['new-537ed005-d997-4adb-b533-aad941b106f4'] = $pane;
  $display->panels['right'][0] = 'new-537ed005-d997-4adb-b533-aad941b106f4';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['content'] = $page;

  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'node_panels_layout';
  $page->task = 'page';
  $page->admin_title = 'Node panels layout';
  $page->admin_description = '';
  $page->path = 'admin/content_manager/node/%node/layout';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'node_access',
        'settings' => array(
          'type' => 'update',
        ),
        'context' => array(
          0 => 'logged-in-user',
          1 => 'argument_entity_id:node_1',
        ),
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array();
  $page->arguments = array(
    'node' => array(
      'id' => 1,
      'identifier' => 'Node (current revision): ID',
      'name' => 'node_current_revision',
      'settings' => array(),
    ),
  );
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_node_panels_layout_panelizer_node';
  $handler->task = 'page';
  $handler->subtask = 'node_panels_layout';
  $handler->handler = 'panelizer_node';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(),
    'context' => 'argument_node_current_revision_1',
  );
  $page->default_handlers[$handler->name] = $handler;
  $pages['node_panels_layout'] = $page;

  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'paddle_content_manager_node_view';
  $page->task = 'page';
  $page->admin_title = 'Backend content display';
  $page->admin_description = 'Displays nodes with their metadata in the administration interface.';
  $page->path = 'admin/content_manager/node/%node/view';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'context_exists',
        'settings' => array(
          'exists' => '1',
        ),
        'context' => 'argument_entity_id:node_1',
        'not' => FALSE,
      ),
      1 => array(
        'name' => 'perm',
        'settings' => array(
          'perm' => 'manage paddle_content_manager',
        ),
        'context' => 'logged-in-user',
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array();
  $page->arguments = array(
    'node' => array(
      'id' => 1,
      'identifier' => 'Node: ID',
      'name' => 'entity_id:node',
      'settings' => array(),
    ),
  );
  $page->conf = array(
    'admin_paths' => TRUE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_paddle_content_manager_node_view_panel_context_landing_page';
  $handler->task = 'page';
  $handler->subtask = 'paddle_content_manager_node_view';
  $handler->handler = 'panelizer_node';
  $handler->weight = -30;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(
      0 => array(
        'identifier' => 'Node current revision',
        'keyword' => 'current_revision',
        'name' => 'current_revision_from_node',
        'context' => 'argument_entity_id:node_1',
        'id' => 1,
      ),
    ),
    'context' => 'relationship_current_revision_from_node_1',
    'access' => array(
      'logic' => 'and',
    ),
  );
  $page->default_handlers[$handler->name] = $handler;
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_paddle_content_manager_node_view_panel_context';
  $handler->task = 'page';
  $handler->subtask = 'paddle_content_manager_node_view';
  $handler->handler = 'panel_context';
  $handler->weight = -29;
  $handler->conf = array(
    'title' => 'Node view',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'logic' => 'and',
    ),
  );
  $display = new panels_display();
  $display->layout = 'twocol_stacked';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'top' => NULL,
      'left' => NULL,
      'right' => NULL,
      'bottom' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = 'fa9942e2-67ef-2e84-a980-9b9fee2b7e48';
  $display->storage_type = 'page_manager';
  $display->storage_id = 'page_paddle_content_manager_node_view_panel_context';
  $display->content = array();
  $display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-13fe7884-2870-a134-35c8-01771bebfbad';
  $pane->panel = 'left';
  $pane->type = 'node_iframe';
  $pane->subtype = 'node_iframe';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
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
  $pane->locks = array();
  $pane->uuid = '13fe7884-2870-a134-35c8-01771bebfbad';
  $display->content['new-13fe7884-2870-a134-35c8-01771bebfbad'] = $pane;
  $display->panels['left'][0] = 'new-13fe7884-2870-a134-35c8-01771bebfbad';
  $pane = new stdClass();
  $pane->pid = 'new-938b9342-7bb0-0ad4-1988-a51e577752c9';
  $pane->panel = 'right';
  $pane->type = 'node_summary_metadata';
  $pane->subtype = 'node_summary_metadata';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
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
  $pane->locks = array();
  $pane->uuid = '938b9342-7bb0-0ad4-1988-a51e577752c9';
  $display->content['new-938b9342-7bb0-0ad4-1988-a51e577752c9'] = $pane;
  $display->panels['right'][0] = 'new-938b9342-7bb0-0ad4-1988-a51e577752c9';
  $pane = new stdClass();
  $pane->pid = 'new-3d3714cf-d319-a344-8138-fda1b376ea1f';
  $pane->panel = 'right';
  $pane->type = 'entity_field';
  $pane->subtype = 'node:field_paddle_author';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'label' => 'title',
    'formatter' => 'text_plain',
    'delta_limit' => 0,
    'delta_offset' => '0',
    'delta_reversed' => FALSE,
    'formatter_settings' => array(),
    'context' => 'argument_entity_id:node_1',
    'override_title' => 0,
    'override_title_text' => '',
    'format' => 'long',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
    'css_class' => 'foldable folded',
  );
  $pane->extras = array();
  $pane->position = 1;
  $pane->locks = array();
  $pane->uuid = '3d3714cf-d319-a344-8138-fda1b376ea1f';
  $display->content['new-3d3714cf-d319-a344-8138-fda1b376ea1f'] = $pane;
  $display->panels['right'][1] = 'new-3d3714cf-d319-a344-8138-fda1b376ea1f';
  $pane = new stdClass();
  $pane->pid = 'new-09a8df74-7c26-0d54-1d77-6f1504f156c8';
  $pane->panel = 'right';
  $pane->type = 'node_created';
  $pane->subtype = 'node_created';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'format' => 'long',
    'context' => 'argument_entity_id:node_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
    'css_class' => 'foldable folded',
  );
  $pane->extras = array();
  $pane->position = 2;
  $pane->locks = array();
  $pane->uuid = '09a8df74-7c26-0d54-1d77-6f1504f156c8';
  $display->content['new-09a8df74-7c26-0d54-1d77-6f1504f156c8'] = $pane;
  $display->panels['right'][2] = 'new-09a8df74-7c26-0d54-1d77-6f1504f156c8';
  $pane = new stdClass();
  $pane->pid = 'new-e561440a-b0ac-5584-2585-eea1158d7903';
  $pane->panel = 'right';
  $pane->type = 'node_editorial_notes';
  $pane->subtype = 'node_editorial_notes';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'context' => 'argument_entity_id:node_1',
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array(
    'css_id' => '',
    'css_class' => 'editorial-notes',
  );
  $pane->extras = array();
  $pane->position = 3;
  $pane->locks = array();
  $pane->uuid = 'e561440a-b0ac-5584-2585-eea1158d7903';
  $display->content['new-e561440a-b0ac-5584-2585-eea1158d7903'] = $pane;
  $display->panels['right'][3] = 'new-e561440a-b0ac-5584-2585-eea1158d7903';
  $pane = new stdClass();
  $pane->pid = 'new-7165ae1b-fb58-22c4-c51a-ac2534475708';
  $pane->panel = 'right';
  $pane->type = 'node_assignee_uid';
  $pane->subtype = 'node_assignee_uid';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
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
  $pane->position = 4;
  $pane->locks = array();
  $pane->uuid = '7165ae1b-fb58-22c4-c51a-ac2534475708';
  $display->content['new-7165ae1b-fb58-22c4-c51a-ac2534475708'] = $pane;
  $display->panels['right'][4] = 'new-7165ae1b-fb58-22c4-c51a-ac2534475708';
  $display->hide_title = PANELS_TITLE_PANE;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['paddle_content_manager_node_view'] = $page;

  return $pages;

}
