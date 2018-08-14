<?php
/**
 * @file
 * opening_hours_sets.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function opening_hours_sets_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'opening_hours_sets';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'opening_hours_sets';
  $view->human_name = 'Opening hours sets';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Opening hours sets';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'manage opening hours sets';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '20';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'ohsid' => 'ohsid',
    'title' => 'title',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'ohsid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['empty'] = TRUE;
  $handler->display->display_options['empty']['area']['content'] = 'No opening hours sets have been created yet.';
  $handler->display->display_options['empty']['area']['format'] = 'full_html';
  /* Field: Opening hours set: Opening hours set ID */
  $handler->display->display_options['fields']['ohsid']['id'] = 'ohsid';
  $handler->display->display_options['fields']['ohsid']['table'] = 'opening_hours_sets';
  $handler->display->display_options['fields']['ohsid']['field'] = 'ohsid';
  $handler->display->display_options['fields']['ohsid']['label'] = '';
  $handler->display->display_options['fields']['ohsid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['ohsid']['element_label_colon'] = FALSE;
  /* Field: Opening hours set: Label */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'opening_hours_sets';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  /* Field: Opening hours set: Edit link */
  $handler->display->display_options['fields']['edit_link']['id'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['table'] = 'opening_hours_sets';
  $handler->display->display_options['fields']['edit_link']['field'] = 'edit_link';
  $handler->display->display_options['fields']['edit_link']['label'] = 'Actions';
  $handler->display->display_options['fields']['edit_link']['element_label_colon'] = FALSE;
  /* Field: Opening hours set: Delete link */
  $handler->display->display_options['fields']['delete_link']['id'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['table'] = 'opening_hours_sets';
  $handler->display->display_options['fields']['delete_link']['field'] = 'delete_link';
  $handler->display->display_options['fields']['delete_link']['label'] = '';
  $handler->display->display_options['fields']['delete_link']['element_label_colon'] = FALSE;
  /* Sort criterion: Opening hours set: Opening hours set ID */
  $handler->display->display_options['sorts']['ohsid']['id'] = 'ohsid';
  $handler->display->display_options['sorts']['ohsid']['table'] = 'opening_hours_sets';
  $handler->display->display_options['sorts']['ohsid']['field'] = 'ohsid';

  /* Display: Opening hours sets overview block */
  $handler = $view->new_display('block', 'Opening hours sets overview block', 'opening_hours_sets_overview_block');
  $translatables['opening_hours_sets'] = array(
    t('Master'),
    t('Opening hours sets'),
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
    t('No opening hours sets have been created yet.'),
    t('Label'),
    t('Actions'),
    t('Opening hours sets overview block'),
  );
  $export['opening_hours_sets'] = $view;

  return $export;
}
