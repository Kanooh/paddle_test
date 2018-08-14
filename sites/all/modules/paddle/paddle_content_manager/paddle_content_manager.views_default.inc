<?php
/**
 * @file
 * paddle_content_manager.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_content_manager_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'content_manager';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node_revision';
  $view->human_name = 'Content manager';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Search all pages';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'manage paddle_content_manager';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['distinct'] = TRUE;
  $handler->display->display_options['query']['options']['pure_distinct'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'allow_secondary' => 1,
      'secondary_label' => 'Advanced options',
    ),
    'title' => array(
      'more_options' => array(
        'is_secondary' => 0,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'body_value' => array(
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_paddle_general_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_paddle_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'type' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'state' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_page_responsible_author_target_id' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'nid' => array(
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'creation_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'last_modified_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'uid_1' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'created' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'changed' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
  );
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'nid_1' => 'nid_1',
    'title' => 'title',
    'type' => 'type',
    'state' => 'state',
    'changed' => 'changed',
    'view_node' => 'view_node',
    'admin_view_link' => 'view_node',
    'edit_node' => 'view_node',
    'page_layout_link' => 'view_node',
    'clone_node' => 'view_node',
    'delete_node' => 'nothing',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nid_1' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'state' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'changed' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'view_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'admin_view_link' => array(
      'align' => '',
      'separator' => ' ',
      'empty_column' => 0,
    ),
    'edit_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'page_layout_link' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'delete_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'clone_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Workbench Moderation: Node */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  /* Field: Bulk operations: Content */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'node';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['relationship'] = 'nid';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '0';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::paddle_content_manager_set_workbench_moderation_state' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'admin/content_manager/node/[nid_1]/view';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'nid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'nid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Content: Link */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['view_node']['label'] = 'Actions';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alt'] = 'View';
  $handler->display->display_options['fields']['view_node']['alter']['link_class'] = 'ui-icon ui-icon-visit-fe';
  /* Field: Paddle Content Manager: Admin view link */
  $handler->display->display_options['fields']['admin_view_link']['id'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['table'] = 'node';
  $handler->display->display_options['fields']['admin_view_link']['field'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['relationship'] = 'nid';
  $handler->display->display_options['fields']['admin_view_link']['label'] = '';
  $handler->display->display_options['fields']['admin_view_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['alt'] = 'Administrative view';
  $handler->display->display_options['fields']['admin_view_link']['alter']['link_class'] = 'ui-icon ui-icon-visit-be';
  $handler->display->display_options['fields']['admin_view_link']['element_label_colon'] = FALSE;
  /* Field: Content: Edit link */
  $handler->display->display_options['fields']['edit_node']['id'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['edit_node']['field'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['edit_node']['label'] = '';
  $handler->display->display_options['fields']['edit_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['edit_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['edit_node']['alter']['alt'] = 'Page properties';
  $handler->display->display_options['fields']['edit_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit-page-properties';
  $handler->display->display_options['fields']['edit_node']['element_label_colon'] = FALSE;
  /* Field: Paddle Content Manager: Page layout link */
  $handler->display->display_options['fields']['page_layout_link']['id'] = 'page_layout_link';
  $handler->display->display_options['fields']['page_layout_link']['table'] = 'node';
  $handler->display->display_options['fields']['page_layout_link']['field'] = 'page_layout_link';
  $handler->display->display_options['fields']['page_layout_link']['relationship'] = 'nid';
  $handler->display->display_options['fields']['page_layout_link']['label'] = '';
  $handler->display->display_options['fields']['page_layout_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['page_layout_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['page_layout_link']['alter']['alt'] = 'Edit layout';
  $handler->display->display_options['fields']['page_layout_link']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit-page-layout';
  $handler->display->display_options['fields']['page_layout_link']['element_label_colon'] = FALSE;
  /* Field: Content: Delete link */
  $handler->display->display_options['fields']['delete_node']['id'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['delete_node']['field'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['delete_node']['label'] = '';
  $handler->display->display_options['fields']['delete_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['alt'] = 'Delete';
  $handler->display->display_options['fields']['delete_node']['alter']['link_class'] = 'ui-icon ui-icon-delete';
  $handler->display->display_options['fields']['delete_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['delete_node']['text'] = 'delete';
  /* Field: Actions */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['ui_name'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[edit_node][page_layout_link]<div class="content-search-actions-grouping accessible-dropdown-trigger hover-expand"><a class="ui-icon ui-icon-add" href="#"></a><ul class="content-manager-more-actions accessible-dropdown-target"><li>[admin_view_link]</li><li>[view_node]</li></ul></div>';
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['relationship'] = 'nid';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['relationship'] = 'nid';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['group'] = 1;
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'Title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'search_title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Body (body) */
  $handler->display->display_options['filters']['body_value']['id'] = 'body_value';
  $handler->display->display_options['filters']['body_value']['table'] = 'field_data_body';
  $handler->display->display_options['filters']['body_value']['field'] = 'body_value';
  $handler->display->display_options['filters']['body_value']['relationship'] = 'nid';
  $handler->display->display_options['filters']['body_value']['operator'] = 'contains';
  $handler->display->display_options['filters']['body_value']['group'] = 1;
  $handler->display->display_options['filters']['body_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['body_value']['expose']['operator_id'] = 'body_value_op';
  $handler->display->display_options['filters']['body_value']['expose']['label'] = 'Body text';
  $handler->display->display_options['filters']['body_value']['expose']['operator'] = 'body_value_op';
  $handler->display->display_options['filters']['body_value']['expose']['identifier'] = 'search_body';
  $handler->display->display_options['filters']['body_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Field: General tags (field_paddle_general_tags) */
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['id'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['field'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['relationship'] = 'nid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['value'] = '';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['operator_id'] = 'field_paddle_general_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['label'] = 'General vocabulary';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['operator'] = 'field_paddle_general_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['identifier'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['vocabulary'] = 'paddle_general';
  /* Filter criterion: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['filters']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['relationship'] = 'nid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['value'] = '';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_paddle_tags_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['operator_id'] = 'field_paddle_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['label'] = 'Tags';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['operator'] = 'field_paddle_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['identifier'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  $handler->display->display_options['filters']['field_paddle_tags_tid']['vocabulary'] = 'paddle_tags';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['relationship'] = 'nid';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Content type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['group'] = 1;
  $handler->display->display_options['filters']['state']['exposed'] = TRUE;
  $handler->display->display_options['filters']['state']['expose']['operator_id'] = 'state_op';
  $handler->display->display_options['filters']['state']['expose']['label'] = 'Status';
  $handler->display->display_options['filters']['state']['expose']['operator'] = 'state_op';
  $handler->display->display_options['filters']['state']['expose']['identifier'] = 'state';
  $handler->display->display_options['filters']['state']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Page responsible author (field_page_responsible_author) */
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['id'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['field'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['relationship'] = 'nid';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['group'] = 1;
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['operator_id'] = 'field_page_responsible_author_target_id_op';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['label'] = 'Responsible author';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['operator'] = 'field_page_responsible_author_target_id_op';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['identifier'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Nid */
  $handler->display->display_options['filters']['nid']['id'] = 'nid';
  $handler->display->display_options['filters']['nid']['table'] = 'node';
  $handler->display->display_options['filters']['nid']['field'] = 'nid';
  $handler->display->display_options['filters']['nid']['relationship'] = 'nid';
  $handler->display->display_options['filters']['nid']['group'] = 1;
  $handler->display->display_options['filters']['nid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['nid']['expose']['operator_id'] = 'nid_op';
  $handler->display->display_options['filters']['nid']['expose']['label'] = 'Node id';
  $handler->display->display_options['filters']['nid']['expose']['operator'] = 'nid_op';
  $handler->display->display_options['filters']['nid']['expose']['identifier'] = 'nid';
  $handler->display->display_options['filters']['nid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Creation author */
  $handler->display->display_options['filters']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['table'] = 'node';
  $handler->display->display_options['filters']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['relationship'] = 'nid';
  $handler->display->display_options['filters']['creation_uid']['group'] = 1;
  $handler->display->display_options['filters']['creation_uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['creation_uid']['expose']['operator_id'] = 'creation_uid_op';
  $handler->display->display_options['filters']['creation_uid']['expose']['label'] = 'Creation author';
  $handler->display->display_options['filters']['creation_uid']['expose']['operator'] = 'creation_uid_op';
  $handler->display->display_options['filters']['creation_uid']['expose']['identifier'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Last modified author */
  $handler->display->display_options['filters']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['last_modified_uid']['field'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['group'] = 1;
  $handler->display->display_options['filters']['last_modified_uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['last_modified_uid']['expose']['operator_id'] = 'last_modified_uid_op';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['label'] = 'Last modified author';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['operator'] = 'last_modified_uid_op';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['identifier'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Validation author */
  $handler->display->display_options['filters']['uid']['id'] = 'uid';
  $handler->display->display_options['filters']['uid']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['filters']['uid']['field'] = 'uid';
  $handler->display->display_options['filters']['uid']['group'] = 1;
  $handler->display->display_options['filters']['uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['uid']['expose']['operator_id'] = 'uid_op';
  $handler->display->display_options['filters']['uid']['expose']['label'] = 'Validation author';
  $handler->display->display_options['filters']['uid']['expose']['operator'] = 'uid_op';
  $handler->display->display_options['filters']['uid']['expose']['identifier'] = 'uid';
  $handler->display->display_options['filters']['uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Assigned author */
  $handler->display->display_options['filters']['uid_1']['id'] = 'uid_1';
  $handler->display->display_options['filters']['uid_1']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['filters']['uid_1']['field'] = 'uid';
  $handler->display->display_options['filters']['uid_1']['group'] = 1;
  $handler->display->display_options['filters']['uid_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['uid_1']['expose']['operator_id'] = 'uid_1_op';
  $handler->display->display_options['filters']['uid_1']['expose']['label'] = 'Assigned author';
  $handler->display->display_options['filters']['uid_1']['expose']['operator'] = 'uid_1_op';
  $handler->display->display_options['filters']['uid_1']['expose']['identifier'] = 'uid_1';
  $handler->display->display_options['filters']['uid_1']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Post date */
  $handler->display->display_options['filters']['created']['id'] = 'created';
  $handler->display->display_options['filters']['created']['table'] = 'node';
  $handler->display->display_options['filters']['created']['field'] = 'created';
  $handler->display->display_options['filters']['created']['relationship'] = 'nid';
  $handler->display->display_options['filters']['created']['operator'] = 'between';
  $handler->display->display_options['filters']['created']['group'] = 1;
  $handler->display->display_options['filters']['created']['exposed'] = TRUE;
  $handler->display->display_options['filters']['created']['expose']['operator_id'] = 'created_op';
  $handler->display->display_options['filters']['created']['expose']['label'] = 'Creation date between';
  $handler->display->display_options['filters']['created']['expose']['operator'] = 'created_op';
  $handler->display->display_options['filters']['created']['expose']['identifier'] = 'created';
  $handler->display->display_options['filters']['created']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Updated date */
  $handler->display->display_options['filters']['changed']['id'] = 'changed';
  $handler->display->display_options['filters']['changed']['table'] = 'node';
  $handler->display->display_options['filters']['changed']['field'] = 'changed';
  $handler->display->display_options['filters']['changed']['relationship'] = 'nid';
  $handler->display->display_options['filters']['changed']['operator'] = 'between';
  $handler->display->display_options['filters']['changed']['group'] = 1;
  $handler->display->display_options['filters']['changed']['exposed'] = TRUE;
  $handler->display->display_options['filters']['changed']['expose']['operator_id'] = 'changed_op';
  $handler->display->display_options['filters']['changed']['expose']['label'] = 'Modification date between';
  $handler->display->display_options['filters']['changed']['expose']['operator'] = 'changed_op';
  $handler->display->display_options['filters']['changed']['expose']['identifier'] = 'changed';
  $handler->display->display_options['filters']['changed']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  $handler->display->display_options['filters']['language']['group'] = 1;
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state_1']['id'] = 'state_1';
  $handler->display->display_options['filters']['state_1']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state_1']['field'] = 'state';
  $handler->display->display_options['filters']['state_1']['operator'] = 'not in';
  $handler->display->display_options['filters']['state_1']['value'] = array(
    'archived' => 'archived',
  );
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['relationship'] = 'nid';
  $handler->display->display_options['filters']['status']['value'] = 'All';
  $handler->display->display_options['filters']['status']['exposed'] = TRUE;
  $handler->display->display_options['filters']['status']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['status']['expose']['label'] = 'Has an online version';
  $handler->display->display_options['filters']['status']['expose']['operator'] = 'status_op';
  $handler->display->display_options['filters']['status']['expose']['identifier'] = 'status';
  $handler->display->display_options['filters']['status']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );

  /* Display: CM - Search content */
  $handler = $view->new_display('page', 'CM - Search content', 'page');
  $handler->display->display_options['defaults']['exposed_form'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'input_required' => 0,
      'text_input_required' => array(
        'text_input_required' => array(
          'value' => 'Select any filter and click on Apply to see results',
          'format' => 'full_html',
        ),
      ),
      'allow_secondary' => 1,
      'secondary_label' => 'Advanced options',
      'secondary_collapse_override' => '2',
    ),
    'title' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'body_value' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_paddle_general_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
            1 => 'vocabulary',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_paddle_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
            1 => 'vocabulary',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'type' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'state' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'field_page_responsible_author_target_id' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'nid' => array(
      'bef_format' => 'default',
      'slider_options' => array(
        'bef_slider_min' => '0',
        'bef_slider_max' => '99999',
        'bef_slider_step' => '1',
        'bef_slider_animate' => '',
        'bef_slider_orientation' => 'horizontal',
      ),
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'creation_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'last_modified_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'uid_1' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
    'created' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
        'datepicker_options' => '',
      ),
    ),
    'changed' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
        'datepicker_options' => '',
      ),
    ),
    'status' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'any_label' => '',
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
        'rewrite' => array(
          'filter_rewrite_values' => '',
        ),
      ),
    ),
  );
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Bulk operations: Content */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'node';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['relationship'] = 'nid';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '0';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::paddle_content_manager_set_workbench_moderation_state' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::paddle_content_manager_set_page_responsible_author' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'admin/content_manager/node/[nid_1]/view';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'nid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'nid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Content: Link */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['view_node']['label'] = 'Actions';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alt'] = 'View';
  $handler->display->display_options['fields']['view_node']['alter']['link_class'] = 'ui-icon ui-icon-visit-fe';
  /* Field: Paddle Content Manager: Admin view link */
  $handler->display->display_options['fields']['admin_view_link']['id'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['table'] = 'node';
  $handler->display->display_options['fields']['admin_view_link']['field'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['relationship'] = 'nid';
  $handler->display->display_options['fields']['admin_view_link']['label'] = '';
  $handler->display->display_options['fields']['admin_view_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['alt'] = 'Administrative view';
  $handler->display->display_options['fields']['admin_view_link']['alter']['link_class'] = 'ui-icon ui-icon-visit-be';
  $handler->display->display_options['fields']['admin_view_link']['element_label_colon'] = FALSE;
  /* Field: Content: Edit link */
  $handler->display->display_options['fields']['edit_node']['id'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['edit_node']['field'] = 'edit_node';
  $handler->display->display_options['fields']['edit_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['edit_node']['label'] = '';
  $handler->display->display_options['fields']['edit_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['edit_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['edit_node']['alter']['alt'] = 'Page properties';
  $handler->display->display_options['fields']['edit_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit-page-properties';
  $handler->display->display_options['fields']['edit_node']['element_label_colon'] = FALSE;
  /* Field: Paddle Content Manager: Page layout link */
  $handler->display->display_options['fields']['page_layout_link']['id'] = 'page_layout_link';
  $handler->display->display_options['fields']['page_layout_link']['table'] = 'node';
  $handler->display->display_options['fields']['page_layout_link']['field'] = 'page_layout_link';
  $handler->display->display_options['fields']['page_layout_link']['relationship'] = 'nid';
  $handler->display->display_options['fields']['page_layout_link']['label'] = '';
  $handler->display->display_options['fields']['page_layout_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['page_layout_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['page_layout_link']['alter']['alt'] = 'Edit layout';
  $handler->display->display_options['fields']['page_layout_link']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit-page-layout';
  $handler->display->display_options['fields']['page_layout_link']['element_label_colon'] = FALSE;
  /* Field: Paddle Content Manager: Archive node link */
  $handler->display->display_options['fields']['archive_node']['id'] = 'archive_node';
  $handler->display->display_options['fields']['archive_node']['table'] = 'node';
  $handler->display->display_options['fields']['archive_node']['field'] = 'archive_node';
  $handler->display->display_options['fields']['archive_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['archive_node']['label'] = '';
  $handler->display->display_options['fields']['archive_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['archive_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['archive_node']['alter']['link_class'] = 'ui-icon ui-icon-archive';
  $handler->display->display_options['fields']['archive_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['archive_node']['modal'] = TRUE;
  /* Field: Paddle Content Manager: Clone link */
  $handler->display->display_options['fields']['clone_node']['id'] = 'clone_node';
  $handler->display->display_options['fields']['clone_node']['table'] = 'node';
  $handler->display->display_options['fields']['clone_node']['field'] = 'clone_node';
  $handler->display->display_options['fields']['clone_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['clone_node']['label'] = 'Clone';
  $handler->display->display_options['fields']['clone_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['clone_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['clone_node']['alter']['alt'] = 'Clone';
  $handler->display->display_options['fields']['clone_node']['alter']['link_class'] = 'ui-icon ui-icon-clone ctools-use-modal';
  $handler->display->display_options['fields']['clone_node']['element_label_colon'] = FALSE;
  /* Field: Actions */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['ui_name'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[edit_node][page_layout_link]<div class="content-search-actions-grouping accessible-dropdown-trigger hover-expand"><a class="ui-icon ui-icon-add" href="#"></a><ul class="content-manager-more-actions accessible-dropdown-target"><li>[admin_view_link]</li><li>[view_node]</li><li>[archive_node]</li><li>[clone_node]</li></ul></div>';
  $handler->display->display_options['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Search';
  $handler->display->display_options['menu']['name'] = 'management';

  /* Display: CSV export */
  $handler = $view->new_display('views_data_export', 'CSV export', 'csv_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Paddle Content Manager: Creation author */
  $handler->display->display_options['fields']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['table'] = 'node';
  $handler->display->display_options['fields']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['relationship'] = 'nid';
  /* Field: Content: Page responsible author */
  $handler->display->display_options['fields']['field_page_responsible_author']['id'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['field'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_page_responsible_author']['label'] = 'Responsible author';
  $handler->display->display_options['fields']['field_page_responsible_author']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_page_responsible_author']['settings'] = array(
    'link' => 0,
  );
  /* Field: Paddle Content Manager: Last modified author */
  $handler->display->display_options['fields']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['fields']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['last_modified_uid']['field'] = 'last_modified_uid';
  /* Field: Paddle Content Manager: Validation author */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  /* Field: Paddle Content Manager: Assigned author */
  $handler->display->display_options['fields']['uid_1']['id'] = 'uid_1';
  $handler->display->display_options['fields']['uid_1']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['fields']['uid_1']['field'] = 'uid';
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'nid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Field: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_general_tags']['label'] = 'General vocabulary';
  $handler->display->display_options['fields']['field_paddle_general_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_general_tags']['delta_offset'] = '0';
  /* Field: Field: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_tags']['delta_offset'] = '0';
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['relationship'] = 'nid';
  $handler->display->display_options['fields']['created']['label'] = 'Creation date';
  $handler->display->display_options['fields']['created']['date_format'] = 'short';
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'nid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified date';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Scheduler: Publish on */
  $handler->display->display_options['fields']['publish_on']['id'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['publish_on']['field'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['relationship'] = 'nid';
  $handler->display->display_options['fields']['publish_on']['date_format'] = 'short';
  /* Field: Scheduler: Unpublish on */
  $handler->display->display_options['fields']['unpublish_on']['id'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['unpublish_on']['field'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['relationship'] = 'nid';
  $handler->display->display_options['fields']['unpublish_on']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['unpublish_on']['date_format'] = 'short';
  /* Field: Content: Language */
  $handler->display->display_options['fields']['language']['id'] = 'language';
  $handler->display->display_options['fields']['language']['table'] = 'node';
  $handler->display->display_options['fields']['language']['field'] = 'language';
  $handler->display->display_options['fields']['language']['relationship'] = 'nid';
  /* Field: Paddle Content Manager: Aliased url */
  $handler->display->display_options['fields']['url']['id'] = 'url';
  $handler->display->display_options['fields']['url']['table'] = 'node';
  $handler->display->display_options['fields']['url']['field'] = 'url';
  $handler->display->display_options['fields']['url']['relationship'] = 'nid';
  $handler->display->display_options['fields']['url']['label'] = 'URL';
  $handler->display->display_options['fields']['url']['element_label_colon'] = FALSE;
  $handler->display->display_options['path'] = 'admin/content_manager/list/export/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['segment_size'] = '100';

  /* Display: XLS export */
  $handler = $view->new_display('views_data_export', 'XLS export', 'xls_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_xls';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'nid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Paddle Content Manager: Creation author */
  $handler->display->display_options['fields']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['table'] = 'node';
  $handler->display->display_options['fields']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['relationship'] = 'nid';
  /* Field: Content: Page responsible author */
  $handler->display->display_options['fields']['field_page_responsible_author']['id'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['field'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_page_responsible_author']['label'] = 'Responsible author';
  $handler->display->display_options['fields']['field_page_responsible_author']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_page_responsible_author']['settings'] = array(
    'link' => 0,
  );
  /* Field: Paddle Content Manager: Last modified author */
  $handler->display->display_options['fields']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['fields']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['last_modified_uid']['field'] = 'last_modified_uid';
  /* Field: Paddle Content Manager: Validation author */
  $handler->display->display_options['fields']['uid_1']['id'] = 'uid_1';
  $handler->display->display_options['fields']['uid_1']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['fields']['uid_1']['field'] = 'uid';
  /* Field: Paddle Content Manager: Assigned author */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'nid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Field: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_general_tags']['label'] = 'General vocabulary';
  $handler->display->display_options['fields']['field_paddle_general_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_general_tags']['delta_offset'] = '0';
  /* Field: Field: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['relationship'] = 'nid';
  $handler->display->display_options['fields']['field_paddle_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_tags']['delta_offset'] = '0';
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['relationship'] = 'nid';
  $handler->display->display_options['fields']['created']['label'] = 'Creation date';
  $handler->display->display_options['fields']['created']['date_format'] = 'short';
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'nid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified date';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Scheduler: Publish on */
  $handler->display->display_options['fields']['publish_on']['id'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['publish_on']['field'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['relationship'] = 'nid';
  $handler->display->display_options['fields']['publish_on']['date_format'] = 'short';
  /* Field: Scheduler: Unpublish on */
  $handler->display->display_options['fields']['unpublish_on']['id'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['unpublish_on']['field'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['relationship'] = 'nid';
  $handler->display->display_options['fields']['unpublish_on']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['unpublish_on']['date_format'] = 'short';
  /* Field: Content: Language */
  $handler->display->display_options['fields']['language']['id'] = 'language';
  $handler->display->display_options['fields']['language']['table'] = 'node';
  $handler->display->display_options['fields']['language']['field'] = 'language';
  $handler->display->display_options['fields']['language']['relationship'] = 'nid';
  /* Field: Paddle Content Manager: Aliased url */
  $handler->display->display_options['fields']['url']['id'] = 'url';
  $handler->display->display_options['fields']['url']['table'] = 'node';
  $handler->display->display_options['fields']['url']['field'] = 'url';
  $handler->display->display_options['fields']['url']['relationship'] = 'nid';
  $handler->display->display_options['fields']['url']['label'] = 'URL';
  $handler->display->display_options['fields']['url']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['url']['alter']['path'] = '[url]';
  $handler->display->display_options['fields']['url']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['url']['element_label_colon'] = FALSE;
  $handler->display->display_options['path'] = 'admin/content_manager/list/export/xls';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['content_manager'] = array(
    t('Master'),
    t('Search all pages'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Advanced options'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Node'),
    t('Content'),
    t('- Choose an operation -'),
    t('Nid'),
    t('Title'),
    t('Type'),
    t('Status'),
    t('Last modified'),
    t('Actions'),
    t('View'),
    t('Administrative view'),
    t('Page properties'),
    t('Edit layout'),
    t('Delete'),
    t('delete'),
    t('[edit_node][page_layout_link]<div class="content-search-actions-grouping accessible-dropdown-trigger hover-expand"><a class="ui-icon ui-icon-add" href="#"></a><ul class="content-manager-more-actions accessible-dropdown-target"><li>[admin_view_link]</li><li>[view_node]</li></ul></div>'),
    t('Body text'),
    t('General vocabulary'),
    t('Tags'),
    t('Content type'),
    t('Responsible author'),
    t('Node id'),
    t('Creation author'),
    t('Last modified author'),
    t('Validation author'),
    t('Assigned author'),
    t('Creation date between'),
    t('Modification date between'),
    t('Has an online version'),
    t('CM - Search content'),
    t('Select any filter and click on Apply to see results'),
    t('Clone'),
    t('[edit_node][page_layout_link]<div class="content-search-actions-grouping accessible-dropdown-trigger hover-expand"><a class="ui-icon ui-icon-add" href="#"></a><ul class="content-manager-more-actions accessible-dropdown-target"><li>[admin_view_link]</li><li>[view_node]</li><li>[archive_node]</li><li>[clone_node]</li></ul></div>'),
    t('CSV export'),
    t('Creation date'),
    t('Last modified date'),
    t('Publish on'),
    t('Unpublish on'),
    t('Language'),
    t('URL'),
    t('XLS export'),
  );
  $export['content_manager'] = $view;

  $view = new view();
  $view->name = 'paddle_archive';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node_revision';
  $view->human_name = 'Archive';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Archived pages';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'manage paddle_content_manager';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['distinct'] = TRUE;
  $handler->display->display_options['query']['options']['pure_distinct'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'better_exposed_filters';
  $handler->display->display_options['exposed_form']['options']['bef'] = array(
    'general' => array(
      'allow_secondary' => 1,
      'secondary_label' => 'Advanced options',
    ),
    'title' => array(
      'more_options' => array(
        'is_secondary' => 0,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'body_value' => array(
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_paddle_general_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_paddle_tags_tid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'type' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 0,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'state' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'field_page_responsible_author_target_id' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'nid' => array(
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'creation_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'last_modified_uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'uid' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'uid_1' => array(
      'bef_format' => 'default',
      'more_options' => array(
        'bef_select_all_none' => FALSE,
        'bef_collapsible' => 0,
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'created' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
    'changed' => array(
      'bef_format' => 'bef_datepicker',
      'more_options' => array(
        'is_secondary' => 1,
        'bef_filter_description' => '',
        'tokens' => array(
          'available' => array(
            0 => 'global_types',
          ),
        ),
      ),
    ),
  );
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'nid_1' => 'nid_1',
    'title' => 'title',
    'type' => 'type',
    'state' => 'state',
    'changed' => 'changed',
    'view_node' => 'view_node',
    'admin_view_link' => 'view_node',
    'nothing' => 'nothing',
    'delete_node' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nid_1' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'state' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'changed' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'view_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'admin_view_link' => array(
      'align' => '',
      'separator' => ' ',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'delete_node' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Content revision: Content */
  $handler->display->display_options['relationships']['vid']['id'] = 'vid';
  $handler->display->display_options['relationships']['vid']['table'] = 'node_revision';
  $handler->display->display_options['relationships']['vid']['field'] = 'vid';
  $handler->display->display_options['relationships']['vid']['label'] = 'Content revision';
  /* Field: Bulk operations: Content */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'node';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['relationship'] = 'vid';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '0';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::paddle_content_manager_restore_node_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['relationship'] = 'vid';
  /* Field: Content revision: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node_revision';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'vid';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'admin/content_manager/node/[nid_1]/view';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'vid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Content revision: Updated date */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'node_revision';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = 'Last modified';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'short';
  /* Field: Content: Link */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['label'] = 'Actions';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alt'] = 'View';
  $handler->display->display_options['fields']['view_node']['alter']['link_class'] = 'ui-icon ui-icon-visit-fe';
  /* Field: Paddle Content Manager: Admin view link */
  $handler->display->display_options['fields']['admin_view_link']['id'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['table'] = 'node';
  $handler->display->display_options['fields']['admin_view_link']['field'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['relationship'] = 'vid';
  $handler->display->display_options['fields']['admin_view_link']['label'] = '';
  $handler->display->display_options['fields']['admin_view_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['alt'] = 'Administrative view';
  $handler->display->display_options['fields']['admin_view_link']['alter']['link_class'] = 'ui-icon ui-icon-visit-be';
  $handler->display->display_options['fields']['admin_view_link']['element_label_colon'] = FALSE;
  /* Field: Content: Delete link */
  $handler->display->display_options['fields']['delete_node']['id'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['delete_node']['field'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['label'] = '';
  $handler->display->display_options['fields']['delete_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['alt'] = 'Delete';
  $handler->display->display_options['fields']['delete_node']['alter']['link_class'] = 'ui-icon ui-icon-delete';
  $handler->display->display_options['fields']['delete_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['delete_node']['text'] = 'delete';
  /* Field: Paddle Content Manager: Restore node link */
  $handler->display->display_options['fields']['restore_node']['id'] = 'restore_node';
  $handler->display->display_options['fields']['restore_node']['table'] = 'node';
  $handler->display->display_options['fields']['restore_node']['field'] = 'restore_node';
  $handler->display->display_options['fields']['restore_node']['relationship'] = 'vid';
  $handler->display->display_options['fields']['restore_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['restore_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['restore_node']['alter']['alt'] = 'Restore';
  $handler->display->display_options['fields']['restore_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-restore';
  $handler->display->display_options['fields']['restore_node']['modal'] = TRUE;
  /* Field: Actions */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['ui_name'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[restore_node][delete_node]';
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['relationship'] = 'vid';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Title */
  $handler->display->display_options['filters']['title']['id'] = 'title';
  $handler->display->display_options['filters']['title']['table'] = 'node';
  $handler->display->display_options['filters']['title']['field'] = 'title';
  $handler->display->display_options['filters']['title']['relationship'] = 'vid';
  $handler->display->display_options['filters']['title']['operator'] = 'contains';
  $handler->display->display_options['filters']['title']['group'] = 1;
  $handler->display->display_options['filters']['title']['exposed'] = TRUE;
  $handler->display->display_options['filters']['title']['expose']['operator_id'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['label'] = 'Title';
  $handler->display->display_options['filters']['title']['expose']['operator'] = 'title_op';
  $handler->display->display_options['filters']['title']['expose']['identifier'] = 'search_title';
  $handler->display->display_options['filters']['title']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Body (body) */
  $handler->display->display_options['filters']['body_value']['id'] = 'body_value';
  $handler->display->display_options['filters']['body_value']['table'] = 'field_data_body';
  $handler->display->display_options['filters']['body_value']['field'] = 'body_value';
  $handler->display->display_options['filters']['body_value']['relationship'] = 'vid';
  $handler->display->display_options['filters']['body_value']['operator'] = 'contains';
  $handler->display->display_options['filters']['body_value']['group'] = 1;
  $handler->display->display_options['filters']['body_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['body_value']['expose']['operator_id'] = 'body_value_op';
  $handler->display->display_options['filters']['body_value']['expose']['label'] = 'Body text';
  $handler->display->display_options['filters']['body_value']['expose']['operator'] = 'body_value_op';
  $handler->display->display_options['filters']['body_value']['expose']['identifier'] = 'search_body';
  $handler->display->display_options['filters']['body_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Field: General tags (field_paddle_general_tags) */
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['id'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['field'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['relationship'] = 'vid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['value'] = '';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['operator_id'] = 'field_paddle_general_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['label'] = 'General vocabulary';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['operator'] = 'field_paddle_general_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['identifier'] = 'field_paddle_general_tags_tid';
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  $handler->display->display_options['filters']['field_paddle_general_tags_tid']['vocabulary'] = 'paddle_general';
  /* Filter criterion: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['filters']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['relationship'] = 'vid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['value'] = '';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_paddle_tags_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['operator_id'] = 'field_paddle_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['label'] = 'Tags';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['operator'] = 'field_paddle_tags_tid_op';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['identifier'] = 'field_paddle_tags_tid';
  $handler->display->display_options['filters']['field_paddle_tags_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  $handler->display->display_options['filters']['field_paddle_tags_tid']['vocabulary'] = 'paddle_tags';
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['relationship'] = 'vid';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Content type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Page responsible author (field_page_responsible_author) */
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['id'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['field'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['relationship'] = 'vid';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['group'] = 1;
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['operator_id'] = 'field_page_responsible_author_target_id_op';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['label'] = 'Responsible author';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['operator'] = 'field_page_responsible_author_target_id_op';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['identifier'] = 'field_page_responsible_author_target_id';
  $handler->display->display_options['filters']['field_page_responsible_author_target_id']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Content: Nid */
  $handler->display->display_options['filters']['nid']['id'] = 'nid';
  $handler->display->display_options['filters']['nid']['table'] = 'node';
  $handler->display->display_options['filters']['nid']['field'] = 'nid';
  $handler->display->display_options['filters']['nid']['relationship'] = 'vid';
  $handler->display->display_options['filters']['nid']['group'] = 1;
  $handler->display->display_options['filters']['nid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['nid']['expose']['operator_id'] = 'nid_op';
  $handler->display->display_options['filters']['nid']['expose']['label'] = 'Node id';
  $handler->display->display_options['filters']['nid']['expose']['operator'] = 'nid_op';
  $handler->display->display_options['filters']['nid']['expose']['identifier'] = 'nid';
  $handler->display->display_options['filters']['nid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Creation author */
  $handler->display->display_options['filters']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['table'] = 'node';
  $handler->display->display_options['filters']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['relationship'] = 'vid';
  $handler->display->display_options['filters']['creation_uid']['group'] = 1;
  $handler->display->display_options['filters']['creation_uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['creation_uid']['expose']['operator_id'] = 'creation_uid_op';
  $handler->display->display_options['filters']['creation_uid']['expose']['label'] = 'Creation author';
  $handler->display->display_options['filters']['creation_uid']['expose']['operator'] = 'creation_uid_op';
  $handler->display->display_options['filters']['creation_uid']['expose']['identifier'] = 'creation_uid';
  $handler->display->display_options['filters']['creation_uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Last modified author */
  $handler->display->display_options['filters']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['last_modified_uid']['field'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['group'] = 1;
  $handler->display->display_options['filters']['last_modified_uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['last_modified_uid']['expose']['operator_id'] = 'last_modified_uid_op';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['label'] = 'Last modified author';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['operator'] = 'last_modified_uid_op';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['identifier'] = 'last_modified_uid';
  $handler->display->display_options['filters']['last_modified_uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Paddle Content Manager: Validation author */
  $handler->display->display_options['filters']['uid']['id'] = 'uid';
  $handler->display->display_options['filters']['uid']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['filters']['uid']['field'] = 'uid';
  $handler->display->display_options['filters']['uid']['group'] = 1;
  $handler->display->display_options['filters']['uid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['uid']['expose']['operator_id'] = 'uid_op';
  $handler->display->display_options['filters']['uid']['expose']['label'] = 'Validation author';
  $handler->display->display_options['filters']['uid']['expose']['operator'] = 'uid_op';
  $handler->display->display_options['filters']['uid']['expose']['identifier'] = 'uid';
  $handler->display->display_options['filters']['uid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Post date */
  $handler->display->display_options['filters']['created']['id'] = 'created';
  $handler->display->display_options['filters']['created']['table'] = 'node';
  $handler->display->display_options['filters']['created']['field'] = 'created';
  $handler->display->display_options['filters']['created']['relationship'] = 'vid';
  $handler->display->display_options['filters']['created']['operator'] = 'between';
  $handler->display->display_options['filters']['created']['group'] = 1;
  $handler->display->display_options['filters']['created']['exposed'] = TRUE;
  $handler->display->display_options['filters']['created']['expose']['operator_id'] = 'created_op';
  $handler->display->display_options['filters']['created']['expose']['label'] = 'Creation date between';
  $handler->display->display_options['filters']['created']['expose']['operator'] = 'created_op';
  $handler->display->display_options['filters']['created']['expose']['identifier'] = 'created';
  $handler->display->display_options['filters']['created']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Updated date */
  $handler->display->display_options['filters']['changed']['id'] = 'changed';
  $handler->display->display_options['filters']['changed']['table'] = 'node';
  $handler->display->display_options['filters']['changed']['field'] = 'changed';
  $handler->display->display_options['filters']['changed']['relationship'] = 'vid';
  $handler->display->display_options['filters']['changed']['operator'] = 'between';
  $handler->display->display_options['filters']['changed']['group'] = 1;
  $handler->display->display_options['filters']['changed']['exposed'] = TRUE;
  $handler->display->display_options['filters']['changed']['expose']['operator_id'] = 'changed_op';
  $handler->display->display_options['filters']['changed']['expose']['label'] = 'Modification date between';
  $handler->display->display_options['filters']['changed']['expose']['operator'] = 'changed_op';
  $handler->display->display_options['filters']['changed']['expose']['identifier'] = 'changed';
  $handler->display->display_options['filters']['changed']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'vid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  $handler->display->display_options['filters']['language']['group'] = 1;
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'archived' => 'archived',
  );

  /* Display: Archive */
  $handler = $view->new_display('page', 'Archive', 'paddle_content_manager_archive');
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Bulk operations: Content */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'node';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['relationship'] = 'vid';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '0';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::paddle_content_manager_restore_node_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'vid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'vid';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'admin/content_manager/node/[nid_1]/view';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'vid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'vid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Content: Link */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['relationship'] = 'vid';
  $handler->display->display_options['fields']['view_node']['label'] = 'Actions';
  $handler->display->display_options['fields']['view_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['alt'] = 'View';
  $handler->display->display_options['fields']['view_node']['alter']['link_class'] = 'ui-icon ui-icon-visit-fe';
  /* Field: Paddle Content Manager: Admin view link */
  $handler->display->display_options['fields']['admin_view_link']['id'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['table'] = 'node';
  $handler->display->display_options['fields']['admin_view_link']['field'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['relationship'] = 'vid';
  $handler->display->display_options['fields']['admin_view_link']['label'] = '';
  $handler->display->display_options['fields']['admin_view_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['alt'] = 'Administrative view';
  $handler->display->display_options['fields']['admin_view_link']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-visit-be';
  $handler->display->display_options['fields']['admin_view_link']['element_label_colon'] = FALSE;
  /* Field: Content: Delete link */
  $handler->display->display_options['fields']['delete_node']['id'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['delete_node']['field'] = 'delete_node';
  $handler->display->display_options['fields']['delete_node']['relationship'] = 'vid';
  $handler->display->display_options['fields']['delete_node']['label'] = '';
  $handler->display->display_options['fields']['delete_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['delete_node']['alter']['alt'] = 'Delete';
  $handler->display->display_options['fields']['delete_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-delete';
  $handler->display->display_options['fields']['delete_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['delete_node']['text'] = 'delete';
  /* Field: Paddle Content Manager: Restore node link */
  $handler->display->display_options['fields']['restore_node']['id'] = 'restore_node';
  $handler->display->display_options['fields']['restore_node']['table'] = 'node';
  $handler->display->display_options['fields']['restore_node']['field'] = 'restore_node';
  $handler->display->display_options['fields']['restore_node']['relationship'] = 'vid';
  $handler->display->display_options['fields']['restore_node']['label'] = '';
  $handler->display->display_options['fields']['restore_node']['exclude'] = TRUE;
  $handler->display->display_options['fields']['restore_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['restore_node']['alter']['alt'] = 'Restore';
  $handler->display->display_options['fields']['restore_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-restore';
  $handler->display->display_options['fields']['restore_node']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['restore_node']['text'] = 'restore';
  $handler->display->display_options['fields']['restore_node']['modal'] = TRUE;
  /* Field: Actions */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['ui_name'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '[restore_node][delete_node]';
  $handler->display->display_options['path'] = 'admin/content_manager/archive';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Archive';
  $handler->display->display_options['menu']['weight'] = '80';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: CSV export */
  $handler = $view->new_display('views_data_export', 'CSV export', 'csv_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'archive.csv';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 0;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'vid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'vid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Paddle Content Manager: Creation author */
  $handler->display->display_options['fields']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['table'] = 'node';
  $handler->display->display_options['fields']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['relationship'] = 'vid';
  /* Field: Content: Page responsible author */
  $handler->display->display_options['fields']['field_page_responsible_author']['id'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['field'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_page_responsible_author']['label'] = 'Responsible author';
  $handler->display->display_options['fields']['field_page_responsible_author']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_page_responsible_author']['settings'] = array(
    'link' => 0,
  );
  /* Field: Paddle Content Manager: Last modified author */
  $handler->display->display_options['fields']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['fields']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['last_modified_uid']['field'] = 'last_modified_uid';
  /* Field: Paddle Content Manager: Validation author */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  /* Field: Paddle Content Manager: Assigned author */
  $handler->display->display_options['fields']['uid_1']['id'] = 'uid_1';
  $handler->display->display_options['fields']['uid_1']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['fields']['uid_1']['field'] = 'uid';
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'vid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Field: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_paddle_general_tags']['label'] = 'General vocabulary';
  $handler->display->display_options['fields']['field_paddle_general_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_general_tags']['delta_offset'] = '0';
  /* Field: Field: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_paddle_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_tags']['delta_offset'] = '0';
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['relationship'] = 'vid';
  $handler->display->display_options['fields']['created']['label'] = 'Creation date';
  $handler->display->display_options['fields']['created']['date_format'] = 'short';
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'vid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified date';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Scheduler: Publish on */
  $handler->display->display_options['fields']['publish_on']['id'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['publish_on']['field'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['relationship'] = 'vid';
  $handler->display->display_options['fields']['publish_on']['date_format'] = 'short';
  /* Field: Scheduler: Unpublish on */
  $handler->display->display_options['fields']['unpublish_on']['id'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['unpublish_on']['field'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['relationship'] = 'vid';
  $handler->display->display_options['fields']['unpublish_on']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['unpublish_on']['date_format'] = 'short';
  /* Field: Content: Language */
  $handler->display->display_options['fields']['language']['id'] = 'language';
  $handler->display->display_options['fields']['language']['table'] = 'node';
  $handler->display->display_options['fields']['language']['field'] = 'language';
  $handler->display->display_options['fields']['language']['relationship'] = 'vid';
  /* Field: Paddle Content Manager: Aliased url */
  $handler->display->display_options['fields']['url']['id'] = 'url';
  $handler->display->display_options['fields']['url']['table'] = 'node';
  $handler->display->display_options['fields']['url']['field'] = 'url';
  $handler->display->display_options['fields']['url']['relationship'] = 'vid';
  $handler->display->display_options['fields']['url']['label'] = 'URL';
  $handler->display->display_options['fields']['url']['element_label_colon'] = FALSE;
  $handler->display->display_options['path'] = 'admin/content_manager/archive/export/csv';
  $handler->display->display_options['displays'] = array(
    'paddle_content_manager_archive' => 'paddle_content_manager_archive',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/archive';
  $handler->display->display_options['segment_size'] = '100';

  /* Display: XLS export */
  $handler = $view->new_display('views_data_export', 'XLS export', 'xls_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_xls';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'archive.xls';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
  $handler->display->display_options['fields']['nid_1']['table'] = 'node';
  $handler->display->display_options['fields']['nid_1']['field'] = 'nid';
  $handler->display->display_options['fields']['nid_1']['relationship'] = 'vid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'vid';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Paddle Content Manager: Creation author */
  $handler->display->display_options['fields']['creation_uid']['id'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['table'] = 'node';
  $handler->display->display_options['fields']['creation_uid']['field'] = 'creation_uid';
  $handler->display->display_options['fields']['creation_uid']['relationship'] = 'vid';
  /* Field: Content: Page responsible author */
  $handler->display->display_options['fields']['field_page_responsible_author']['id'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['table'] = 'field_data_field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['field'] = 'field_page_responsible_author';
  $handler->display->display_options['fields']['field_page_responsible_author']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_page_responsible_author']['label'] = 'Responsible author';
  $handler->display->display_options['fields']['field_page_responsible_author']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_page_responsible_author']['settings'] = array(
    'link' => 0,
  );
  /* Field: Paddle Content Manager: Last modified author */
  $handler->display->display_options['fields']['last_modified_uid']['id'] = 'last_modified_uid';
  $handler->display->display_options['fields']['last_modified_uid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['last_modified_uid']['field'] = 'last_modified_uid';
  /* Field: Paddle Content Manager: Validation author */
  $handler->display->display_options['fields']['uid_1']['id'] = 'uid_1';
  $handler->display->display_options['fields']['uid_1']['table'] = 'paddle_content_manager_validate_user';
  $handler->display->display_options['fields']['uid_1']['field'] = 'uid';
  /* Field: Paddle Content Manager: Assigned author */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  /* Field: Content: Type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'node';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'vid';
  /* Field: Workbench Moderation: State */
  $handler->display->display_options['fields']['state']['id'] = 'state';
  $handler->display->display_options['fields']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['fields']['state']['field'] = 'state';
  $handler->display->display_options['fields']['state']['label'] = 'Status';
  $handler->display->display_options['fields']['state']['machine_name'] = 0;
  /* Field: Field: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'field_data_field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_paddle_general_tags']['label'] = 'General vocabulary';
  $handler->display->display_options['fields']['field_paddle_general_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_general_tags']['delta_offset'] = '0';
  /* Field: Field: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['relationship'] = 'vid';
  $handler->display->display_options['fields']['field_paddle_tags']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_tags']['delta_offset'] = '0';
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['relationship'] = 'vid';
  $handler->display->display_options['fields']['created']['label'] = 'Creation date';
  $handler->display->display_options['fields']['created']['date_format'] = 'short';
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['relationship'] = 'vid';
  $handler->display->display_options['fields']['changed']['label'] = 'Last modified date';
  $handler->display->display_options['fields']['changed']['date_format'] = 'short';
  /* Field: Scheduler: Publish on */
  $handler->display->display_options['fields']['publish_on']['id'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['publish_on']['field'] = 'publish_on';
  $handler->display->display_options['fields']['publish_on']['relationship'] = 'vid';
  $handler->display->display_options['fields']['publish_on']['date_format'] = 'short';
  /* Field: Scheduler: Unpublish on */
  $handler->display->display_options['fields']['unpublish_on']['id'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['table'] = 'scheduler';
  $handler->display->display_options['fields']['unpublish_on']['field'] = 'unpublish_on';
  $handler->display->display_options['fields']['unpublish_on']['relationship'] = 'vid';
  $handler->display->display_options['fields']['unpublish_on']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['unpublish_on']['date_format'] = 'short';
  /* Field: Content: Language */
  $handler->display->display_options['fields']['language']['id'] = 'language';
  $handler->display->display_options['fields']['language']['table'] = 'node';
  $handler->display->display_options['fields']['language']['field'] = 'language';
  $handler->display->display_options['fields']['language']['relationship'] = 'vid';
  /* Field: Paddle Content Manager: Aliased url */
  $handler->display->display_options['fields']['url']['id'] = 'url';
  $handler->display->display_options['fields']['url']['table'] = 'node';
  $handler->display->display_options['fields']['url']['field'] = 'url';
  $handler->display->display_options['fields']['url']['relationship'] = 'vid';
  $handler->display->display_options['fields']['url']['label'] = 'URL';
  $handler->display->display_options['fields']['url']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['url']['alter']['path'] = '[url]';
  $handler->display->display_options['fields']['url']['alter']['absolute'] = TRUE;
  $handler->display->display_options['fields']['url']['element_label_colon'] = FALSE;
  $handler->display->display_options['path'] = 'admin/content_manager/archive/export/xls';
  $handler->display->display_options['displays'] = array(
    'paddle_content_manager_archive' => 'paddle_content_manager_archive',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/archive';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['paddle_archive'] = array(
    t('Master'),
    t('Archived pages'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Advanced options'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Content revision'),
    t('Content'),
    t('- Choose an operation -'),
    t('Nid'),
    t('Title'),
    t('Type'),
    t('Status'),
    t('Last modified'),
    t('Actions'),
    t('View'),
    t('Administrative view'),
    t('Delete'),
    t('delete'),
    t('Restore node link'),
    t('Restore'),
    t('[restore_node][delete_node]'),
    t('Body text'),
    t('General vocabulary'),
    t('Tags'),
    t('Content type'),
    t('Responsible author'),
    t('Node id'),
    t('Creation author'),
    t('Last modified author'),
    t('Validation author'),
    t('Creation date between'),
    t('Modification date between'),
    t('Archive'),
    t('restore'),
    t('CSV export'),
    t('Assigned author'),
    t('Creation date'),
    t('Last modified date'),
    t('Publish on'),
    t('Unpublish on'),
    t('Language'),
    t('URL'),
    t('XLS export'),
  );
  $export['paddle_archive'] = $view;

  $view = new view();
  $view->name = 'page_responsible_users';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'users';
  $view->human_name = 'Page responsible users';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['disable_sql_rewrite'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_user'] = FALSE;
  /* Sort criterion: User: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'users';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  /* Filter criterion: User: Active */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'users';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'name' => 'name',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $translatables['page_responsible_users'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Entity Reference'),
  );
  $export['page_responsible_users'] = $view;

  return $export;
}
