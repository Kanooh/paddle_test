<?php
/**
 * @file
 * paddle_dashboard.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_dashboard_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_dashboard_status';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node_revision';
  $view->human_name = 'Paddle Dashboard Status';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '5';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'view_node' => 'view_node',
    'admin_view_link' => 'admin_view_link',
  );
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'scheduled';
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['empty'] = TRUE;
  $handler->display->display_options['empty']['area']['content'] = 'No results found.';
  $handler->display->display_options['empty']['area']['format'] = 'full_html';
  /* Relationship: Workbench Moderation: Node */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  $handler->display->display_options['relationships']['nid']['required'] = TRUE;
  /* Field: Paddle Dashboard: Title */
  $handler->display->display_options['fields']['dashboard_title_1']['id'] = 'dashboard_title_1';
  $handler->display->display_options['fields']['dashboard_title_1']['table'] = 'node';
  $handler->display->display_options['fields']['dashboard_title_1']['field'] = 'dashboard_title';
  $handler->display->display_options['fields']['dashboard_title_1']['relationship'] = 'nid';
  $handler->display->display_options['fields']['dashboard_title_1']['label'] = '';
  $handler->display->display_options['fields']['dashboard_title_1']['element_label_colon'] = FALSE;
  /* Field: Content: Link */
  $handler->display->display_options['fields']['view_node']['id'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['table'] = 'views_entity_node';
  $handler->display->display_options['fields']['view_node']['field'] = 'view_node';
  $handler->display->display_options['fields']['view_node']['relationship'] = 'nid';
  $handler->display->display_options['fields']['view_node']['label'] = '';
  $handler->display->display_options['fields']['view_node']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['view_node']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-visit';
  $handler->display->display_options['fields']['view_node']['element_label_colon'] = FALSE;
  /* Field: Paddle Content Manager: Admin view link */
  $handler->display->display_options['fields']['admin_view_link']['id'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['table'] = 'node';
  $handler->display->display_options['fields']['admin_view_link']['field'] = 'admin_view_link';
  $handler->display->display_options['fields']['admin_view_link']['relationship'] = 'nid';
  $handler->display->display_options['fields']['admin_view_link']['label'] = '';
  $handler->display->display_options['fields']['admin_view_link']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['admin_view_link']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit';
  $handler->display->display_options['fields']['admin_view_link']['element_label_colon'] = FALSE;
  /* Sort criterion: Content revision: Updated date */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'node_revision';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'draft' => 'draft',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language_1']['id'] = 'language_1';
  $handler->display->display_options['filters']['language_1']['table'] = 'node';
  $handler->display->display_options['filters']['language_1']['field'] = 'language';
  $handler->display->display_options['filters']['language_1']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language_1']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';

  /* Display: My concepts */
  $handler = $view->new_display('panel_pane', 'My concepts', 'panel_pane_my_concepts');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'My concepts';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['user_query_parameter'] = 'creation_uid';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'draft';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Content: Author uid */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['relationship'] = 'nid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'current_user';
  $handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['pane_title'] = 'My concepts';

  /* Display: All to check */
  $handler = $view->new_display('panel_pane', 'All to check', 'panel_pane_all_to_check');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'All to check';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'to_check';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'to_check' => 'to_check',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'All to check';

  /* Display: Mine In Review */
  $handler = $view->new_display('panel_pane', 'Mine In Review', 'panel_pane_mine_in_review');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Mine in review';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['user_query_parameter'] = 'uid_1';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'needs_review';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Paddle Content Manager: Assigned author */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'current_user';
  $handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'needs_review' => 'needs_review',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'Mine in review';

  /* Display: All in review */
  $handler = $view->new_display('panel_pane', 'All in review', 'panel_pane_all_in_review');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'All in review';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'needs_review';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'needs_review' => 'needs_review',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'All in review';

  /* Display: All published */
  $handler = $view->new_display('panel_pane', 'All published', 'panel_pane_all_published');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'All recent published';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'published';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['relationship'] = 'nid';
  $handler->display->display_options['filters']['status']['value'] = '1';
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'All published';

  /* Display: All unpublished */
  $handler = $view->new_display('panel_pane', 'All unpublished', 'panel_pane_all_unpublished');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'All recent unpublished (offline)';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'offline';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'offline' => 'offline',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'All unpublished';

  /* Display: Mine to check */
  $handler = $view->new_display('panel_pane', 'Mine to check', 'panel_pane_mine_to_check');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Mine to check';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['user_query_parameter'] = 'uid_to_check';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'to_check';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Paddle Content Manager: Assigned author */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'paddle_content_manager_revision_assignee';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'current_user';
  $handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'to_check' => 'to_check',
  );
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language']['id'] = 'language';
  $handler->display->display_options['filters']['language']['table'] = 'node';
  $handler->display->display_options['filters']['language']['field'] = 'language';
  $handler->display->display_options['filters']['language']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'Mine to check';

  /* Display: Planned publications */
  $handler = $view->new_display('panel_pane', 'Planned publications', 'panel_pane_planned_publications');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Planned publications';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'scheduled';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Content revision: Updated date */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'node_revision';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language_1']['id'] = 'language_1';
  $handler->display->display_options['filters']['language_1']['table'] = 'node';
  $handler->display->display_options['filters']['language_1']['field'] = 'language';
  $handler->display->display_options['filters']['language_1']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language_1']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Scheduler: Publish on */
  $handler->display->display_options['filters']['publish_on']['id'] = 'publish_on';
  $handler->display->display_options['filters']['publish_on']['table'] = 'scheduler';
  $handler->display->display_options['filters']['publish_on']['field'] = 'publish_on';
  $handler->display->display_options['filters']['publish_on']['relationship'] = 'nid';
  $handler->display->display_options['filters']['publish_on']['operator'] = '>';
  $handler->display->display_options['filters']['publish_on']['value']['value'] = '1987-06-03 00:00:00';
  $handler->display->display_options['filters']['publish_on']['expose']['operator_id'] = 'publish_on_op';
  $handler->display->display_options['filters']['publish_on']['expose']['label'] = 'Publish on';
  $handler->display->display_options['filters']['publish_on']['expose']['operator'] = 'publish_on_op';
  $handler->display->display_options['filters']['publish_on']['expose']['identifier'] = 'publish_on';
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'scheduled' => 'scheduled',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'Planned publications';

  /* Display: Planned unpublications */
  $handler = $view->new_display('panel_pane', 'Planned unpublications', 'panel_pane_planned_unpublications');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Planned unpublications';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Paddle Dashboard: More link */
  $handler->display->display_options['footer']['more']['id'] = 'more';
  $handler->display->display_options['footer']['more']['table'] = 'paddle_dashboard_global';
  $handler->display->display_options['footer']['more']['field'] = 'more';
  $handler->display->display_options['footer']['more']['label'] = 'More';
  $handler->display->display_options['footer']['more']['path'] = 'admin/content_manager/list/search';
  $handler->display->display_options['footer']['more']['workbench_moderation_state'] = 'scheduled_depublication';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Content revision: Updated date */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'node_revision';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Language */
  $handler->display->display_options['filters']['language_1']['id'] = 'language_1';
  $handler->display->display_options['filters']['language_1']['table'] = 'node';
  $handler->display->display_options['filters']['language_1']['field'] = 'language';
  $handler->display->display_options['filters']['language_1']['relationship'] = 'nid';
  $handler->display->display_options['filters']['language_1']['value'] = array(
    '***CURRENT_LANGUAGE***' => '***CURRENT_LANGUAGE***',
  );
  /* Filter criterion: Scheduler: Unpublish on */
  $handler->display->display_options['filters']['unpublish_on']['id'] = 'unpublish_on';
  $handler->display->display_options['filters']['unpublish_on']['table'] = 'scheduler';
  $handler->display->display_options['filters']['unpublish_on']['field'] = 'unpublish_on';
  $handler->display->display_options['filters']['unpublish_on']['relationship'] = 'nid';
  $handler->display->display_options['filters']['unpublish_on']['operator'] = '>';
  $handler->display->display_options['filters']['unpublish_on']['value']['value'] = '1987-06-03 00:00:00';
  /* Filter criterion: Workbench Moderation: State */
  $handler->display->display_options['filters']['state']['id'] = 'state';
  $handler->display->display_options['filters']['state']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['state']['field'] = 'state';
  $handler->display->display_options['filters']['state']['value'] = array(
    'scheduled' => 'scheduled',
    'published' => 'published',
  );
  /* Filter criterion: Workbench Moderation: Current */
  $handler->display->display_options['filters']['is_current']['id'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['table'] = 'workbench_moderation_node_history';
  $handler->display->display_options['filters']['is_current']['field'] = 'is_current';
  $handler->display->display_options['filters']['is_current']['value'] = '1';
  $handler->display->display_options['pane_title'] = 'Planned unpublications';
  $translatables['paddle_dashboard_status'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('More'),
    t('No results found.'),
    t('Node'),
    t('My concepts'),
    t('All'),
    t('View panes'),
    t('All to check'),
    t('Mine In Review'),
    t('Mine in review'),
    t('All in review'),
    t('All published'),
    t('All recent published'),
    t('All unpublished'),
    t('All recent unpublished (offline)'),
    t('Mine to check'),
    t('Planned publications'),
    t('Publish on'),
    t('Planned unpublications'),
  );
  $export['paddle_dashboard_status'] = $view;

  return $export;
}
