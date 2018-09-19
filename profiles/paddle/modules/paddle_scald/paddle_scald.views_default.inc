<?php
/**
 * @file
 * paddle_scald.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_scald_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'media_library';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'search_api_index_paddle_atoms';
  $view->human_name = 'Media Library';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Manage assets';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'access scald library';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['exposed_form']['options']['expose_sort_order'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '24';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['default_field_elements'] = FALSE;
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No media have been added to the library yet.';
  /* Field: Indexed Atoms: Rendered Media library item */
  $handler->display->display_options['fields']['library_item']['id'] = 'library_item';
  $handler->display->display_options['fields']['library_item']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['library_item']['field'] = 'library_item';
  $handler->display->display_options['fields']['library_item']['label'] = '';
  $handler->display->display_options['fields']['library_item']['element_label_colon'] = FALSE;
  /* Sort criterion: Indexed Atoms: Created */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Search: Fulltext search */
  $handler->display->display_options['filters']['search_api_views_fulltext']['id'] = 'search_api_views_fulltext';
  $handler->display->display_options['filters']['search_api_views_fulltext']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['filters']['search_api_views_fulltext']['field'] = 'search_api_views_fulltext';
  $handler->display->display_options['filters']['search_api_views_fulltext']['operator'] = 'OR';
  $handler->display->display_options['filters']['search_api_views_fulltext']['exposed'] = TRUE;
  $handler->display->display_options['filters']['search_api_views_fulltext']['expose']['operator_id'] = 'search_api_views_fulltext_op';
  $handler->display->display_options['filters']['search_api_views_fulltext']['expose']['label'] = 'Search';
  $handler->display->display_options['filters']['search_api_views_fulltext']['expose']['operator'] = 'search_api_views_fulltext_op';
  $handler->display->display_options['filters']['search_api_views_fulltext']['expose']['identifier'] = 'search_api_views_fulltext';
  $handler->display->display_options['filters']['search_api_views_fulltext']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['search_api_views_fulltext']['min_length'] = '3';
  $handler->display->display_options['filters']['search_api_views_fulltext']['fields'] = array(
    'field_scald_alt_tag' => 'field_scald_alt_tag',
    'field_scald_caption' => 'field_scald_caption',
    'field_scald_description' => 'field_scald_description',
    'title' => 'title',
  );
  /* Filter criterion: Indexed Atoms: Atom type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'file' => 'file',
    'image' => 'image',
    'video' => 'video',
  );
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  $handler->display->display_options['filters']['type']['expose']['reduce'] = 1;
  /* Filter criterion: Indexed Atoms: Tags */
  $handler->display->display_options['filters']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['filters']['field_paddle_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['filters']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['filters']['field_paddle_tags']['value'] = array();
  $handler->display->display_options['filters']['field_paddle_tags']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_tags']['expose']['operator_id'] = 'field_paddle_tags_op';
  $handler->display->display_options['filters']['field_paddle_tags']['expose']['label'] = 'Tags';
  $handler->display->display_options['filters']['field_paddle_tags']['expose']['operator'] = 'field_paddle_tags_op';
  $handler->display->display_options['filters']['field_paddle_tags']['expose']['identifier'] = 'tags';
  $handler->display->display_options['filters']['field_paddle_tags']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );
  /* Filter criterion: Indexed Atoms: General tags */
  $handler->display->display_options['filters']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['filters']['field_paddle_general_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['filters']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['filters']['field_paddle_general_tags']['value'] = array();
  $handler->display->display_options['filters']['field_paddle_general_tags']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_paddle_general_tags']['expose']['operator_id'] = 'field_paddle_general_tags_op';
  $handler->display->display_options['filters']['field_paddle_general_tags']['expose']['label'] = 'General vocabulary';
  $handler->display->display_options['filters']['field_paddle_general_tags']['expose']['operator'] = 'field_paddle_general_tags_op';
  $handler->display->display_options['filters']['field_paddle_general_tags']['expose']['identifier'] = 'general_tags';
  $handler->display->display_options['filters']['field_paddle_general_tags']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
  );

  /* Display: Media library overview */
  $handler = $view->new_display('page', 'Media library overview', 'media_libray_page');
  $handler->display->display_options['path'] = 'admin/content_manager/assets';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Manage assets';
  $handler->display->display_options['menu']['weight'] = '25';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: CSV export */
  $handler = $view->new_display('views_data_export', 'CSV export', 'paddle_scald_media_library_csv_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'media_library_overview.csv';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 0;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['newline_token'] = '1';
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Indexed Atoms: Atom type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['label'] = 'Type';
  $handler->display->display_options['fields']['type']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['type']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['type']['format_name'] = 1;
  /* Field: Indexed Atoms: Path to a Media library item */
  $handler->display->display_options['fields']['atom_file_path']['id'] = 'atom_file_path';
  $handler->display->display_options['fields']['atom_file_path']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['atom_file_path']['field'] = 'atom_file_path';
  $handler->display->display_options['fields']['atom_file_path']['label'] = 'Path';
  $handler->display->display_options['fields']['atom_file_path']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_entity'] = 0;
  /* Field: Indexed Atoms: Alternative text */
  $handler->display->display_options['fields']['field_scald_alt_tag']['id'] = 'field_scald_alt_tag';
  $handler->display->display_options['fields']['field_scald_alt_tag']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_scald_alt_tag']['field'] = 'field_scald_alt_tag';
  $handler->display->display_options['fields']['field_scald_alt_tag']['label'] = 'Alt text';
  $handler->display->display_options['fields']['field_scald_alt_tag']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Description */
  $handler->display->display_options['fields']['field_scald_description']['id'] = 'field_scald_description';
  $handler->display->display_options['fields']['field_scald_description']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_scald_description']['field'] = 'field_scald_description';
  $handler->display->display_options['fields']['field_scald_description']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_tags']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['field_paddle_tags']['view_mode'] = 'full';
  $handler->display->display_options['fields']['field_paddle_tags']['bypass_access'] = 0;
  /* Field: Indexed Atoms: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_general_tags']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['field_paddle_general_tags']['view_mode'] = 'full';
  $handler->display->display_options['fields']['field_paddle_general_tags']['bypass_access'] = 0;
  /* Field: Indexed Atoms: Thumbnail of a Scald Video atom */
  $handler->display->display_options['fields']['video_thumbnail']['id'] = 'video_thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['video_thumbnail']['field'] = 'video_thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['label'] = 'Thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Subtitles */
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['id'] = 'paddle_scald_video_subtitles';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['field'] = 'paddle_scald_video_subtitles';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['type'] = 'file_url_plain';
  /* Field: Indexed Atoms: Publisher */
  $handler->display->display_options['fields']['publisher']['id'] = 'publisher';
  $handler->display->display_options['fields']['publisher']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['publisher']['field'] = 'publisher';
  $handler->display->display_options['fields']['publisher']['label'] = 'Uploader';
  $handler->display->display_options['fields']['publisher']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['publisher']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['publisher']['view_mode'] = 'full';
  $handler->display->display_options['fields']['publisher']['bypass_access'] = 0;
  $handler->display->display_options['path'] = 'admin/content_manager/assets/export/csv';
  $handler->display->display_options['displays'] = array(
    'media_libray_page' => 'media_libray_page',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/assets';
  $handler->display->display_options['segment_size'] = '100';

  /* Display: XLS export */
  $handler = $view->new_display('views_data_export', 'XLS export', 'paddle_scald_media_library_xls_export');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_xls';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = 'media_library_overview.xls';
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Indexed Atoms: Atom type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['label'] = 'Type';
  $handler->display->display_options['fields']['type']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['type']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['type']['format_name'] = 1;
  /* Field: Indexed Atoms: Path to a Media library item */
  $handler->display->display_options['fields']['atom_file_path']['id'] = 'atom_file_path';
  $handler->display->display_options['fields']['atom_file_path']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['atom_file_path']['field'] = 'atom_file_path';
  $handler->display->display_options['fields']['atom_file_path']['label'] = 'Path';
  $handler->display->display_options['fields']['atom_file_path']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['atom_file_path']['alter']['path'] = '[atom_file_path]';
  $handler->display->display_options['fields']['atom_file_path']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_entity'] = 0;
  /* Field: Indexed Atoms: Alternative text */
  $handler->display->display_options['fields']['field_scald_alt_tag']['id'] = 'field_scald_alt_tag';
  $handler->display->display_options['fields']['field_scald_alt_tag']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_scald_alt_tag']['field'] = 'field_scald_alt_tag';
  $handler->display->display_options['fields']['field_scald_alt_tag']['label'] = 'Alt text';
  $handler->display->display_options['fields']['field_scald_alt_tag']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Description */
  $handler->display->display_options['fields']['field_scald_description']['id'] = 'field_scald_description';
  $handler->display->display_options['fields']['field_scald_description']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_scald_description']['field'] = 'field_scald_description';
  $handler->display->display_options['fields']['field_scald_description']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Tags */
  $handler->display->display_options['fields']['field_paddle_tags']['id'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_paddle_tags']['field'] = 'field_paddle_tags';
  $handler->display->display_options['fields']['field_paddle_tags']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_tags']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['field_paddle_tags']['view_mode'] = 'full';
  $handler->display->display_options['fields']['field_paddle_tags']['bypass_access'] = 0;
  /* Field: Indexed Atoms: General tags */
  $handler->display->display_options['fields']['field_paddle_general_tags']['id'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['field_paddle_general_tags']['field'] = 'field_paddle_general_tags';
  $handler->display->display_options['fields']['field_paddle_general_tags']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_general_tags']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['field_paddle_general_tags']['view_mode'] = 'full';
  $handler->display->display_options['fields']['field_paddle_general_tags']['bypass_access'] = 0;
  /* Field: Indexed Atoms: Thumbnail of a Scald Video atom */
  $handler->display->display_options['fields']['video_thumbnail']['id'] = 'video_thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['video_thumbnail']['field'] = 'video_thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['label'] = 'Thumbnail';
  $handler->display->display_options['fields']['video_thumbnail']['element_label_colon'] = FALSE;
  /* Field: Indexed Atoms: Subtitles */
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['id'] = 'paddle_scald_video_subtitles';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['field'] = 'paddle_scald_video_subtitles';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['alter']['path'] = '[paddle_scald_video_subtitles]';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['paddle_scald_video_subtitles']['type'] = 'file_url_plain';
  /* Field: Indexed Atoms: Publisher */
  $handler->display->display_options['fields']['publisher']['id'] = 'publisher';
  $handler->display->display_options['fields']['publisher']['table'] = 'search_api_index_paddle_atoms';
  $handler->display->display_options['fields']['publisher']['field'] = 'publisher';
  $handler->display->display_options['fields']['publisher']['label'] = 'Uploader';
  $handler->display->display_options['fields']['publisher']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['publisher']['link_to_entity'] = 0;
  $handler->display->display_options['fields']['publisher']['view_mode'] = 'full';
  $handler->display->display_options['fields']['publisher']['bypass_access'] = 0;
  $handler->display->display_options['path'] = 'admin/content_manager/assets/export/xls';
  $handler->display->display_options['displays'] = array(
    'media_libray_page' => 'media_libray_page',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['return_path'] = 'admin/content_manager/assets';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['media_library'] = array(
    t('Master'),
    t('Manage assets'),
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
    t('No media have been added to the library yet.'),
    t('Search'),
    t('Type'),
    t('Tags'),
    t('General vocabulary'),
    t('Media library overview'),
    t('CSV export'),
    t('Path'),
    t('Title'),
    t('Alt text'),
    t('Description'),
    t('General tags'),
    t('Thumbnail'),
    t('Subtitles'),
    t('Uploader'),
    t('XLS export'),
  );
  $export['media_library'] = $view;

  return $export;
}
