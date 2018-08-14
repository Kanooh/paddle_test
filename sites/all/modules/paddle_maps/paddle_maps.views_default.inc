<?php
/**
 * @file
 * paddle_maps.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_maps_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_maps';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'search_api_index_node_index';
  $view->human_name = 'Paddle maps';
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
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'gmapextended';
  $handler->display->display_options['style_options']['fallback_values'] = array(
    'text' => '',
    'rmt' => '',
    'iwq' => '',
    'iwo' => '',
    'link' => '',
    'latitude' => '',
    'longitude' => '',
    'title' => '',
    'markername' => '',
    'offset' => '',
  );
  $handler->display->display_options['style_options']['field_purposes'] = array(
    'nid' => 'offset',
    'field_paddle_address_geocode_lat' => 'latitude',
    'field_paddle_address_geocode_lon' => 'longitude',
  );
  $handler->display->display_options['style_options']['datasource'] = 'fields';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Indexed Node: Node ID */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  /* Field: Indexed Node: Content types */
  $handler->display->display_options['fields']['field_map_content_types']['id'] = 'field_map_content_types';
  $handler->display->display_options['fields']['field_map_content_types']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_map_content_types']['field'] = 'field_map_content_types';
  $handler->display->display_options['fields']['field_map_content_types']['label'] = '';
  $handler->display->display_options['fields']['field_map_content_types']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_map_content_types']['delta_offset'] = '0';
  /* Field: Geofield: Latitude (indexed) */
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['id'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['field'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['precision'] = '0';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['link_to_entity'] = 0;
  /* Field: Geofield: Longitude (indexed) */
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['id'] = 'field_paddle_address_geocode_lon';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['field'] = 'field_paddle_address_geocode_lon';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['precision'] = '0';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['link_to_entity'] = 0;
  /* Field: Indexed Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = 'Title';
  $handler->display->display_options['fields']['title']['link_to_entity'] = 0;
  /* Sort criterion: Indexed Node: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
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
    'body:summary' => 'body:summary',
    'body:value' => 'body:value',
    'search_api_aggregation_4' => 'search_api_aggregation_4',
  );
  /* Filter criterion: Indexed Node: Content type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array();
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Geofield: Latitude (indexed) */
  $handler->display->display_options['filters']['field_paddle_address_geocode_lat']['id'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['filters']['field_paddle_address_geocode_lat']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['filters']['field_paddle_address_geocode_lat']['field'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['filters']['field_paddle_address_geocode_lat']['operator'] = 'not empty';
  /* Filter criterion: Indexed Node: Status */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = array(
    1 => '1',
  );

  /* Display: Block Maps */
  $handler = $view->new_display('block', 'Block Maps', 'block_maps');
  $handler->display->display_options['defaults']['empty'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Indexed Node: Node ID */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['nid']['link_to_entity'] = 0;
  /* Field: Geofield: Latitude (indexed) */
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['id'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['field'] = 'field_paddle_address_geocode_lat';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['precision'] = '0';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lat']['link_to_entity'] = 0;
  /* Field: Geofield: Longitude (indexed) */
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['id'] = 'field_paddle_address_geocode_lon';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['field'] = 'field_paddle_address_geocode_lon';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['precision'] = '0';
  $handler->display->display_options['fields']['field_paddle_address_geocode_lon']['link_to_entity'] = 0;
  /* Field: Indexed Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['title']['empty_zero'] = TRUE;
  $handler->display->display_options['fields']['title']['link_to_entity'] = 1;

  /* Display: Block List */
  $handler = $view->new_display('block', 'Block List', 'block_maps_text');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'list';
  $handler->display->display_options['style_options']['row_class'] = 'search-result';
  $handler->display->display_options['style_options']['type'] = 'ol';
  $handler->display->display_options['style_options']['class'] = 'search-results';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Indexed Node: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_type'] = 'div';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_class'] = 'featured-image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['hide_alter_empty'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  $handler->display->display_options['fields']['field_paddle_featured_image']['type'] = 'paddle_search_featured_image';
  /* Field: Indexed Node: Node ID */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['nid']['separator'] = '';
  $handler->display->display_options['fields']['nid']['link_to_entity'] = 0;
  /* Field: Indexed Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'search_api_index_node_index';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_entity'] = 0;
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = '';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = 'More information about [title]';
  $handler->display->display_options['fields']['nothing']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nothing']['alter']['path'] = 'node/[nid]';
  $handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
  $translatables['paddle_maps'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Node ID'),
    t('Latitude (indexed)'),
    t('Longitude (indexed)'),
    t('Title'),
    t('All'),
    t('Block Maps'),
    t('T'),
    t('Block List'),
    t('More information about [title]'),
  );
  $export['paddle_maps'] = $view;

  return $export;
}
