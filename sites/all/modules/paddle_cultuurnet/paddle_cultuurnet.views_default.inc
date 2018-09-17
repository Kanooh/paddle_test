<?php
/**
 * @file
 * paddle_cultuurnet.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_cultuurnet_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'cultuurnet_blocks';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'cdb_items';
  $view->human_name = 'Cultuurnet Blocks';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'In the region';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '4';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Cdb items: Picture */
  $handler->display->display_options['fields']['picture']['id'] = 'picture';
  $handler->display->display_options['fields']['picture']['table'] = 'cdb_items';
  $handler->display->display_options['fields']['picture']['field'] = 'picture';
  $handler->display->display_options['fields']['picture']['label'] = '';
  $handler->display->display_options['fields']['picture']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['picture']['override'] = 0;
  $handler->display->display_options['fields']['picture']['width'] = '200';
  $handler->display->display_options['fields']['picture']['height'] = '200';
  $handler->display->display_options['fields']['picture']['scale'] = 'both';
  $handler->display->display_options['fields']['picture']['link'] = 1;
  /* Field: Cdb items: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'cdb_items';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['override'] = 0;
  $handler->display->display_options['fields']['title']['link'] = 1;
  /* Field: Cdb items: Calendar summary */
  $handler->display->display_options['fields']['when']['id'] = 'when';
  $handler->display->display_options['fields']['when']['table'] = 'cdb_items';
  $handler->display->display_options['fields']['when']['field'] = 'when';
  $handler->display->display_options['fields']['when']['label'] = '';
  $handler->display->display_options['fields']['when']['element_label_colon'] = FALSE;
  /* Sort criterion: Cdb items: Date */
  $handler->display->display_options['sorts']['date']['id'] = 'date';
  $handler->display->display_options['sorts']['date']['table'] = 'cdb_items';
  $handler->display->display_options['sorts']['date']['field'] = 'date';
  $handler->display->display_options['sorts']['date']['order'] = 'ASC';
  /* Filter criterion: Cdb items: Tags */
  $handler->display->display_options['filters']['tags']['id'] = 'tags';
  $handler->display->display_options['filters']['tags']['table'] = 'cdb_items';
  $handler->display->display_options['filters']['tags']['field'] = 'tags';
  $handler->display->display_options['filters']['tags']['keyword'] = array(
    0 => array(
      'value' => 'UiTinMijnRegio',
      'not' => 0,
    ),
    1 => array(
      'value' => '',
      'not' => 0,
    ),
    2 => array(
      'value' => '',
      'not' => 0,
    ),
    3 => array(
      'value' => '',
      'not' => 0,
    ),
    4 => array(
      'value' => '',
      'not' => 0,
    ),
    5 => array(
      'value' => '',
      'not' => 0,
    ),
    6 => array(
      'value' => '',
      'not' => 0,
    ),
    7 => array(
      'value' => '',
      'not' => 0,
    ),
    8 => array(
      'value' => '',
      'not' => 0,
    ),
    9 => array(
      'value' => '',
      'not' => 0,
    ),
  );

  /* Display: In the region */
  $handler = $view->new_display('block', 'In the region', 'in_the_region');

  /* Display: Spotlight */
  $handler = $view->new_display('block', 'Spotlight', 'spotlight');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Spotlight';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Cdb items: Tags */
  $handler->display->display_options['filters']['tags']['id'] = 'tags';
  $handler->display->display_options['filters']['tags']['table'] = 'cdb_items';
  $handler->display->display_options['filters']['tags']['field'] = 'tags';
  $handler->display->display_options['filters']['tags']['keyword'] = array(
    0 => array(
      'value' => 'Website Nazareth',
      'not' => 0,
    ),
    1 => array(
      'value' => '',
      'not' => 0,
    ),
    2 => array(
      'value' => '',
      'not' => 0,
    ),
    3 => array(
      'value' => '',
      'not' => 0,
    ),
    4 => array(
      'value' => '',
      'not' => 0,
    ),
    5 => array(
      'value' => '',
      'not' => 0,
    ),
    6 => array(
      'value' => '',
      'not' => 0,
    ),
    7 => array(
      'value' => '',
      'not' => 0,
    ),
    8 => array(
      'value' => '',
      'not' => 0,
    ),
    9 => array(
      'value' => '',
      'not' => 0,
    ),
  );
  $translatables['cultuurnet_blocks'] = array(
    t('Master'),
    t('In the region'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Spotlight'),
  );
  $export['cultuurnet_blocks'] = $view;

  return $export;
}
