<?php
/**
 * @file
 * paddle_reference_tracker.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_reference_tracker_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_reference_tracker_references';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'reference_tracker';
  $view->human_name = 'Scald references';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Scald references';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'role';
  $handler->display->display_options['access']['role'] = array(
    2 => '2',
  );
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['distinct'] = TRUE;
  $handler->display->display_options['query']['options']['pure_distinct'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['style_plugin'] = 'table';
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['empty'] = TRUE;
  $handler->display->display_options['empty']['area']['content'] = 'No references available.';
  $handler->display->display_options['empty']['area']['format'] = 'full_html';
  /* Field: Reference tracker: Entity bundle */
  $handler->display->display_options['fields']['entity_bundle']['id'] = 'entity_bundle';
  $handler->display->display_options['fields']['entity_bundle']['table'] = 'reference_tracker';
  $handler->display->display_options['fields']['entity_bundle']['field'] = 'entity_bundle';
  $handler->display->display_options['fields']['entity_bundle']['label'] = 'Type';
  /* Field: Reference tracker: Entity ID */
  $handler->display->display_options['fields']['entity_id']['id'] = 'entity_id';
  $handler->display->display_options['fields']['entity_id']['table'] = 'reference_tracker';
  $handler->display->display_options['fields']['entity_id']['field'] = 'entity_id';
  $handler->display->display_options['fields']['entity_id']['label'] = 'Title';
  /* Contextual filter: Reference tracker: Reference entity ID */
  $handler->display->display_options['arguments']['reference_entity_id']['id'] = 'reference_entity_id';
  $handler->display->display_options['arguments']['reference_entity_id']['table'] = 'reference_tracker';
  $handler->display->display_options['arguments']['reference_entity_id']['field'] = 'reference_entity_id';
  $handler->display->display_options['arguments']['reference_entity_id']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['reference_entity_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['reference_entity_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['reference_entity_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['reference_entity_id']['summary_options']['items_per_page'] = '25';
  /* Filter criterion: Reference tracker: Reference entity type */
  $handler->display->display_options['filters']['reference_entity_type']['id'] = 'reference_entity_type';
  $handler->display->display_options['filters']['reference_entity_type']['table'] = 'reference_tracker';
  $handler->display->display_options['filters']['reference_entity_type']['field'] = 'reference_entity_type';
  $handler->display->display_options['filters']['reference_entity_type']['value'] = 'scald_atom';

  /* Display: Atom references */
  $handler = $view->new_display('page', 'Atom references', 'paddle_atom_references_page');
  $handler->display->display_options['path'] = 'admin/paddle_scald/%/references';
  $handler->display->display_options['menu']['title'] = 'Scald references';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['paddle_reference_tracker_references'] = array(
    t('Master'),
    t('Scald references'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('No references available.'),
    t('Type'),
    t('Title'),
    t('All'),
    t('Atom references'),
  );
  $export['paddle_reference_tracker_references'] = $view;

  return $export;
}
