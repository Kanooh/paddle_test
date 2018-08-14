<?php
/**
 * @file
 * paddle_advanced_search.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_advanced_search_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_advanced_search';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'search_api_index_node_index';
  $view->human_name = 'Paddle advanced search';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['search_api_bypass_access'] = 0;
  $handler->display->display_options['query']['options']['entity_access'] = 0;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['tags']['first'] = 'first';
  $handler->display->display_options['pager']['options']['tags']['previous'] = 'previous';
  $handler->display->display_options['pager']['options']['tags']['next'] = 'next';
  $handler->display->display_options['pager']['options']['tags']['last'] = 'last';
  $handler->display->display_options['style_plugin'] = 'paddle_advanced_search_results';
  /* Field: Indexed Node: Node ID */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  /* Contextual filter: Indexed Node: Content type */
  $handler->display->display_options['arguments']['type']['id'] = 'type';
  $handler->display->display_options['arguments']['type']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['arguments']['type']['field'] = 'type';
  $handler->display->display_options['arguments']['type']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['type']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['type']['break_phrase'] = 1;
  $handler->display->display_options['arguments']['type']['not'] = 0;
  /* Contextual filter: Indexed Node: General tags */
  $handler->display->display_options['arguments']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_paddle_general_tags']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['validate_options']['vocabularies'] = array(
    'paddle_general' => 'paddle_general',
  );
  $handler->display->display_options['arguments']['field_paddle_general_tags']['validate_options']['type'] = 'tids';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['field_paddle_general_tags']['break_phrase'] = 1;
  $handler->display->display_options['arguments']['field_paddle_general_tags']['not'] = 0;
  /* Contextual filter: Search: Fulltext search */
  $handler->display->display_options['arguments']['search_api_views_fulltext']['id'] = 'search_api_views_fulltext';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['field'] = 'search_api_views_fulltext';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['exception']['value'] = '';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['search_api_views_fulltext']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['search_api_views_fulltext']['not'] = 0;
  $handler->display->display_options['arguments']['search_api_views_fulltext']['fields'] = array(
    'attachments_paddle_scald_file' => 'attachments_paddle_scald_file',
    'body:summary' => 'body:summary',
    'search_api_aggregation_1' => 'search_api_aggregation_1',
    'search_api_aggregation_3' => 'search_api_aggregation_3',
    'search_api_aggregation_4' => 'search_api_aggregation_4',
    'search_api_panelizer_content' => 'search_api_panelizer_content',
  );
  /* Filter criterion: Indexed Node: Content type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['operator'] = '<>';
  $handler->display->display_options['filters']['type']['value'] = array(
    'paddle_advanced_search_page' => 'paddle_advanced_search_page',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  $translatables['paddle_advanced_search'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('first'),
    t('previous'),
    t('next'),
    t('last'),
    t('Node ID'),
    t('.'),
    t(','),
    t('All'),
  );
  $export['paddle_advanced_search'] = $view;

  return $export;
}
