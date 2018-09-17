<?php
/**
 * @file
 * paddle_pane_collection.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_pane_collection_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_pane_collections';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'paddle_pane_collection';
  $view->human_name = 'Pane collections';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Pane collections';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'ppcid' => 'ppcid',
    'title' => 'title',
    'nothing_2' => 'nothing',
    'nothing' => 'nothing',
    'nothing_1' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'ppcid' => array(
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
    'nothing_2' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing_1' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Field: Paddle Pane Collection: Paddle pane collection ID */
  $handler->display->display_options['fields']['ppcid']['id'] = 'ppcid';
  $handler->display->display_options['fields']['ppcid']['table'] = 'paddle_pane_collection';
  $handler->display->display_options['fields']['ppcid']['field'] = 'ppcid';
  $handler->display->display_options['fields']['ppcid']['exclude'] = TRUE;
  /* Field: Paddle Pane Collection: Label */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'paddle_pane_collection';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = 'Title';
  /* Field: Edit link */
  $handler->display->display_options['fields']['nothing_2']['id'] = 'nothing_2';
  $handler->display->display_options['fields']['nothing_2']['table'] = 'views';
  $handler->display->display_options['fields']['nothing_2']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing_2']['ui_name'] = 'Edit link';
  $handler->display->display_options['fields']['nothing_2']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing_2']['alter']['text'] = 'Edit';
  $handler->display->display_options['fields']['nothing_2']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nothing_2']['alter']['path'] = 'admin/paddle-pane-collection/nojs/edit/[ppcid]';
  $handler->display->display_options['fields']['nothing_2']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit ctools-use-modal';
  /* Field: Layout link */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['ui_name'] = 'Layout link';
  $handler->display->display_options['fields']['nothing']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = 'Layout';
  $handler->display->display_options['fields']['nothing']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nothing']['alter']['path'] = 'admin/paddle-pane-collection/edit/[ppcid]';
  $handler->display->display_options['fields']['nothing']['alter']['link_class'] = 'ui-icon ui-icon-hiddentext ui-icon-edit-page-layout';
  /* Field: Delete link */
  $handler->display->display_options['fields']['nothing_1']['id'] = 'nothing_1';
  $handler->display->display_options['fields']['nothing_1']['table'] = 'views';
  $handler->display->display_options['fields']['nothing_1']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing_1']['ui_name'] = 'Delete link';
  $handler->display->display_options['fields']['nothing_1']['label'] = 'Actions';
  $handler->display->display_options['fields']['nothing_1']['alter']['text'] = 'Delete';
  $handler->display->display_options['fields']['nothing_1']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['nothing_1']['alter']['path'] = 'admin/paddle-pane-collection/nojs/delete/[ppcid]';
  $handler->display->display_options['fields']['nothing_1']['alter']['link_class'] = 'ctools-use-modal ui-icon ui-icon-hiddentext ui-icon-delete';

  /* Display: Pane collections */
  $handler = $view->new_display('block', 'Pane collections', 'pane_collections');
  $translatables['paddle_pane_collections'] = array(
    t('Master'),
    t('Pane collections'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Paddle pane collection ID'),
    t('.'),
    t(','),
    t('Title'),
    t('Actions'),
    t('Edit'),
    t('Layout'),
    t('Delete'),
  );
  $export['paddle_pane_collections'] = $view;

  return $export;
}
