<?php
/**
 * @file
 * paddle_publication.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_publication_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'publications_export';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Publications export';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Publications export';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Contextual filter: Content: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'raw';
  $handler->display->display_options['arguments']['nid']['default_argument_options']['index'] = '1';
  $handler->display->display_options['arguments']['nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['summary_options']['items_per_page'] = '25';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'paddle_publication' => 'paddle_publication',
  );

  /* Display: Publications export */
  $handler = $view->new_display('page', 'Publications export', 'page');
  $handler->display->display_options['display_description'] = 'Publications export';
  $handler->display->display_options['defaults']['access'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'administer views';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['exclude'] = TRUE;
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['path'] = 'risexport';

  /* Display: Data export */
  $handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['style_plugin'] = 'views_data_export_txt';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = '%1-title.txt';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Publication Type */
  $handler->display->display_options['fields']['field_paddle_kce_publ_type']['id'] = 'field_paddle_kce_publ_type';
  $handler->display->display_options['fields']['field_paddle_kce_publ_type']['table'] = 'field_data_field_paddle_kce_publ_type';
  $handler->display->display_options['fields']['field_paddle_kce_publ_type']['field'] = 'field_paddle_kce_publ_type';
  $handler->display->display_options['fields']['field_paddle_kce_publ_type']['label'] = 'TY';
  $handler->display->display_options['fields']['field_paddle_kce_publ_type']['element_label_colon'] = FALSE;
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = 'TI';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Authors */
  $handler->display->display_options['fields']['field_paddle_kce_authors']['id'] = 'field_paddle_kce_authors';
  $handler->display->display_options['fields']['field_paddle_kce_authors']['table'] = 'field_data_field_paddle_kce_authors';
  $handler->display->display_options['fields']['field_paddle_kce_authors']['field'] = 'field_paddle_kce_authors';
  $handler->display->display_options['fields']['field_paddle_kce_authors']['label'] = 'AU';
  $handler->display->display_options['fields']['field_paddle_kce_authors']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_kce_authors']['delta_offset'] = '0';
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = 'AB';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  /* Field: Content: Collections */
  $handler->display->display_options['fields']['field_paddle_kce_collections']['id'] = 'field_paddle_kce_collections';
  $handler->display->display_options['fields']['field_paddle_kce_collections']['table'] = 'field_data_field_paddle_kce_collections';
  $handler->display->display_options['fields']['field_paddle_kce_collections']['field'] = 'field_paddle_kce_collections';
  $handler->display->display_options['fields']['field_paddle_kce_collections']['label'] = 'T2';
  $handler->display->display_options['fields']['field_paddle_kce_collections']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_collections']['type'] = 'taxonomy_term_reference_plain';
  /* Field: Content: Place Published */
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['id'] = 'field_paddle_kce_place_published';
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['table'] = 'field_data_field_paddle_kce_place_published';
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['field'] = 'field_paddle_kce_place_published';
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['label'] = 'CY';
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_place_published']['type'] = 'text_plain';
  /* Field: Content: Year of publication */
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['id'] = 'field_paddle_kce_publication_y';
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['table'] = 'field_data_field_paddle_kce_publication_y';
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['field'] = 'field_paddle_kce_publication_y';
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['label'] = 'PY';
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_publication_y']['type'] = 'taxonomy_term_reference_plain';
  /* Field: Content: Publication language */
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['id'] = 'field_paddle_kce_publication_lan';
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['table'] = 'field_data_field_paddle_kce_publication_lan';
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['field'] = 'field_paddle_kce_publication_lan';
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['label'] = 'LA';
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_publication_lan']['type'] = 'text_plain';
  /* Field: Content: Type */
  $handler->display->display_options['fields']['field_paddle_kce_type']['id'] = 'field_paddle_kce_type';
  $handler->display->display_options['fields']['field_paddle_kce_type']['table'] = 'field_data_field_paddle_kce_type';
  $handler->display->display_options['fields']['field_paddle_kce_type']['field'] = 'field_paddle_kce_type';
  $handler->display->display_options['fields']['field_paddle_kce_type']['label'] = 'M3';
  $handler->display->display_options['fields']['field_paddle_kce_type']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_type']['type'] = 'text_plain';
  /* Field: Content: Publisher */
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['id'] = 'field_paddle_kce_publisher';
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['table'] = 'field_data_field_paddle_kce_publisher';
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['field'] = 'field_paddle_kce_publisher';
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['label'] = 'PB';
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_publisher']['type'] = 'text_plain';
  /* Field: Content: Number */
  $handler->display->display_options['fields']['field_paddle_kce_number']['id'] = 'field_paddle_kce_number';
  $handler->display->display_options['fields']['field_paddle_kce_number']['table'] = 'field_data_field_paddle_kce_number';
  $handler->display->display_options['fields']['field_paddle_kce_number']['field'] = 'field_paddle_kce_number';
  $handler->display->display_options['fields']['field_paddle_kce_number']['label'] = 'SN';
  $handler->display->display_options['fields']['field_paddle_kce_number']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_number']['type'] = 'text_plain';
  /* Field: Content: Legal Depot Number */
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['id'] = 'field_paddle_kce_depot_number';
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['table'] = 'field_data_field_paddle_kce_depot_number';
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['field'] = 'field_paddle_kce_depot_number';
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['label'] = 'VO';
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_depot_number']['type'] = 'text_plain';
  /* Field: Content: Keywords */
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['id'] = 'field_paddle_kce_keywords';
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['table'] = 'field_data_field_paddle_kce_keywords';
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['field'] = 'field_paddle_kce_keywords';
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['label'] = 'KW';
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_paddle_kce_keywords']['delta_offset'] = '0';
  /* Field: Content: URL */
  $handler->display->display_options['fields']['field_paddle_kce_url']['id'] = 'field_paddle_kce_url';
  $handler->display->display_options['fields']['field_paddle_kce_url']['table'] = 'field_data_field_paddle_kce_url';
  $handler->display->display_options['fields']['field_paddle_kce_url']['field'] = 'field_paddle_kce_url';
  $handler->display->display_options['fields']['field_paddle_kce_url']['label'] = 'L1';
  $handler->display->display_options['fields']['field_paddle_kce_url']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_kce_url']['settings'] = array(
    'trim_length' => '',
    'nofollow' => 0,
  );
  $handler->display->display_options['path'] = 'risexport.txt';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['publications_export'] = array(
    t('Master'),
    t('Publications export'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Title'),
    t('Body'),
    t('All'),
    t('Data export'),
    t('TY'),
    t('TI'),
    t('AU'),
    t('AB'),
    t('T2'),
    t('CY'),
    t('PY'),
    t('LA'),
    t('M3'),
    t('PB'),
    t('SN'),
    t('VO'),
    t('KW'),
    t('L1'),
  );
  $export['publications_export'] = $view;

  return $export;
}
