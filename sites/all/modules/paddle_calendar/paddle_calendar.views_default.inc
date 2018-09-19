<?php
/**
 * @file
 * paddle_calendar.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_calendar_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'calendar_item_ical';
  $view->description = 'A iCal feed for a single calendar item.';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Calendar item iCal';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'long',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '200',
  );
  /* Field: Content: Location */
  $handler->display->display_options['fields']['field_paddle_calendar_location']['id'] = 'field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['table'] = 'field_data_field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['field'] = 'field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['element_label_colon'] = FALSE;
  /* Contextual filter: Content: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['nid']['exception']['value'] = '';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'node';
  $handler->display->display_options['arguments']['nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['nid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['nid']['validate']['type'] = 'node';
  $handler->display->display_options['arguments']['nid']['validate_options']['types'] = array(
    'calendar_item' => 'calendar_item',
  );
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'calendar_item' => 'calendar_item',
  );

  /* Display: Feed */
  $handler = $view->new_display('feed', 'Feed', 'ical');
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['style_plugin'] = 'date_ical';
  $handler->display->display_options['style_options']['cal_name'] = '';
  $handler->display->display_options['style_options']['disable_webcal'] = TRUE;
  $handler->display->display_options['row_plugin'] = 'date_ical_fields';
  $handler->display->display_options['row_options']['date_field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['row_options']['title_field'] = 'title';
  $handler->display->display_options['row_options']['description_field'] = 'body';
  $handler->display->display_options['row_options']['location_field'] = 'field_paddle_calendar_location';
  $handler->display->display_options['row_options']['additional_settings'] = array(
    'skip_blank_dates' => 0,
  );
  $handler->display->display_options['path'] = 'node/%/paddle-calendar/event.ics';
  $handler->display->display_options['displays'] = array(
    'default' => 'default',
  );
  $translatables['calendar_item_ical'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('All'),
    t('Feed'),
  );
  $export['calendar_item_ical'] = $view;

  $view = new view();
  $view->name = 'calendar_pane';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Calendar pane';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'date_views_pager';
  $handler->display->display_options['pager']['options']['link_format'] = 'clean';
  $handler->display->display_options['style_plugin'] = 'calendar_style';
  $handler->display->display_options['style_options']['name_size'] = '3';
  $handler->display->display_options['style_options']['mini'] = '1';
  $handler->display->display_options['style_options']['with_weekno'] = '0';
  $handler->display->display_options['style_options']['multiday_theme'] = '1';
  $handler->display->display_options['style_options']['theme_style'] = '1';
  $handler->display->display_options['style_options']['max_items'] = '0';
  $handler->display->display_options['row_plugin'] = 'calendar_entity';
  $handler->display->display_options['row_options']['colors']['calendar_colors_type'] = array(
    'basic_page' => '#ffffff',
    'calendar_item' => '#ffffff',
    'landing_page' => '#ffffff',
    'paddle_overview_page' => '#ffffff',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Sort criterion: Content: Event dates -  start date (field_paddle_calendar_date) */
  $handler->display->display_options['sorts']['field_paddle_calendar_date_value']['id'] = 'field_paddle_calendar_date_value';
  $handler->display->display_options['sorts']['field_paddle_calendar_date_value']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['sorts']['field_paddle_calendar_date_value']['field'] = 'field_paddle_calendar_date_value';
  /* Contextual filter: Date: Date (node) */
  $handler->display->display_options['arguments']['date_argument']['id'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['table'] = 'node';
  $handler->display->display_options['arguments']['date_argument']['field'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['default_action'] = 'default';
  $handler->display->display_options['arguments']['date_argument']['default_argument_type'] = 'date';
  $handler->display->display_options['arguments']['date_argument']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['date_argument']['date_fields'] = array(
    'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value' => 'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value',
  );
  /* Contextual filter: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['vocabularies'] = array(
    'paddle_tags' => 'paddle_tags',
  );
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['type'] = 'tids';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['fail'] = 'ignore';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['break_phrase'] = TRUE;
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
    'calendar_item' => 'calendar_item',
  );

  /* Display: Month calendar view */
  $handler = $view->new_display('block', 'Month calendar view', 'month_calendar_view');

  /* Display: Day view */
  $handler = $view->new_display('block', 'Day view', 'day_view');
  $handler->display->display_options['defaults']['use_ajax'] = FALSE;
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['header'] = FALSE;
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = '%1';
  $handler->display->display_options['header']['area_text_custom']['tokenize'] = TRUE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_type'] = '0';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_wrapper_class'] = 'calendar-item-time';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'time_only',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Date: Date (node) */
  $handler->display->display_options['arguments']['date_argument']['id'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['table'] = 'node';
  $handler->display->display_options['arguments']['date_argument']['field'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['default_action'] = 'default';
  $handler->display->display_options['arguments']['date_argument']['default_argument_type'] = 'date';
  $handler->display->display_options['arguments']['date_argument']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['date_argument']['granularity'] = 'day';
  $handler->display->display_options['arguments']['date_argument']['date_fields'] = array(
    'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value' => 'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value',
  );
  /* Contextual filter: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['vocabularies'] = array(
    'paddle_tags' => 'paddle_tags',
  );
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['type'] = 'tids';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['fail'] = 'ignore';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['break_phrase'] = TRUE;

  /* Display: Week list view */
  $handler = $view->new_display('block', 'Week list view', 'week_list_view');
  $handler->display->display_options['defaults']['use_ajax'] = FALSE;
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'date_views_pager';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'field_paddle_calendar_date',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['style_options']['row_class'] = '[field_paddle_calendar_date]';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['id'] = 'field_paddle_calendar_date_1';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_type'] = '0';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_wrapper_class'] = 'calendar-item-time';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['settings'] = array(
    'format_type' => 'time_only',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'day_only',
    'fromto' => 'value',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Date: Date (node) */
  $handler->display->display_options['arguments']['date_argument']['id'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['table'] = 'node';
  $handler->display->display_options['arguments']['date_argument']['field'] = 'date_argument';
  $handler->display->display_options['arguments']['date_argument']['default_action'] = 'default';
  $handler->display->display_options['arguments']['date_argument']['default_argument_type'] = 'date';
  $handler->display->display_options['arguments']['date_argument']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['date_argument']['granularity'] = 'week';
  $handler->display->display_options['arguments']['date_argument']['date_fields'] = array(
    'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value' => 'field_data_field_paddle_calendar_date.field_paddle_calendar_date_value',
  );
  /* Contextual filter: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['vocabularies'] = array(
    'paddle_tags' => 'paddle_tags',
  );
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['type'] = 'tids';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['fail'] = 'ignore';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['break_phrase'] = TRUE;

  /* Display: Month list view */
  $handler = $view->new_display('block', 'Month list view', 'month_list_view');
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'date_views_pager';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'field_paddle_calendar_date',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['empty'] = FALSE;
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No calendar items this month.';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['id'] = 'field_paddle_calendar_date_1';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_type'] = '0';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['element_wrapper_class'] = 'calendar-item-time';
  $handler->display->display_options['fields']['field_paddle_calendar_date_1']['settings'] = array(
    'format_type' => 'time_only',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'paddle_core_date_medium',
    'fromto' => 'value',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );

  /* Display: iCal */
  $handler = $view->new_display('feed', 'iCal', 'ical');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'date_ical';
  $handler->display->display_options['style_options']['cal_name'] = '';
  $handler->display->display_options['row_plugin'] = 'date_ical_fields';
  $handler->display->display_options['row_options']['date_field'] = 'first_available';
  $handler->display->display_options['row_options']['title_field'] = 'title';
  $handler->display->display_options['row_options']['description_field'] = 'body';
  $handler->display->display_options['row_options']['location_field'] = 'field_paddle_calendar_location';
  $handler->display->display_options['row_options']['additional_settings'] = array(
    'skip_blank_dates' => 1,
  );
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'long',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['label'] = '';
  $handler->display->display_options['fields']['body']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '200',
  );
  /* Field: Content: Location */
  $handler->display->display_options['fields']['field_paddle_calendar_location']['id'] = 'field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['table'] = 'field_data_field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['field'] = 'field_paddle_calendar_location';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_location']['element_label_colon'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Global: Null */
  $handler->display->display_options['arguments']['null']['id'] = 'null';
  $handler->display->display_options['arguments']['null']['table'] = 'views';
  $handler->display->display_options['arguments']['null']['field'] = 'null';
  $handler->display->display_options['arguments']['null']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['null']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['null']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['null']['summary_options']['items_per_page'] = '25';
  /* Contextual filter: Field: Tags (field_paddle_tags) */
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['id'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['table'] = 'field_data_field_paddle_tags';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['field'] = 'field_paddle_tags_tid';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['vocabularies'] = array(
    'paddle_tags' => 'paddle_tags',
  );
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate_options']['type'] = 'tids';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['validate']['fail'] = 'ignore';
  $handler->display->display_options['arguments']['field_paddle_tags_tid']['break_phrase'] = TRUE;
  $handler->display->display_options['path'] = 'paddle-calendar/%/%/calendar.ics';
  $handler->display->display_options['displays'] = array(
    'month_calendar_view' => 'month_calendar_view',
    'week_list_view' => 'week_list_view',
    'month_list_view' => 'month_list_view',
    'month_list_view_image' => 'month_list_view_image',
    'default' => 0,
    'day_view' => 0,
  );

  /* Display: Month list view with image */
  $handler = $view->new_display('block', 'Month list view with image', 'month_list_view_image');
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'date_views_pager';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'field_paddle_calendar_date',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['empty'] = FALSE;
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No calendar items this month.';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Featured image */
  $handler->display->display_options['fields']['field_paddle_featured_image']['id'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['table'] = 'field_data_field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['field'] = 'field_paddle_featured_image';
  $handler->display->display_options['fields']['field_paddle_featured_image']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_featured_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_featured_image']['click_sort_column'] = 'sid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Event dates */
  $handler->display->display_options['fields']['field_paddle_calendar_date']['id'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['table'] = 'field_data_field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['field'] = 'field_paddle_calendar_date';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['label'] = '';
  $handler->display->display_options['fields']['field_paddle_calendar_date']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_paddle_calendar_date']['settings'] = array(
    'format_type' => 'full_month_no_time',
    'fromto' => 'value',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  $translatables['calendar_pane'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('All'),
    t('Month calendar view'),
    t('Day view'),
    t('%1'),
    t('Week list view'),
    t('Month list view'),
    t('No calendar items this month.'),
    t('iCal'),
    t('Month list view with image'),
  );
  $export['calendar_pane'] = $view;

  return $export;
}
