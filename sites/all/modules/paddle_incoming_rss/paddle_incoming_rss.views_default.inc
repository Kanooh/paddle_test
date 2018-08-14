<?php
/**
 * @file
 * paddle_incoming_rss.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function paddle_incoming_rss_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'paddle_incoming_rss_feed_items_list';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'paddle_incoming_rss_feed_item';
  $view->human_name = 'Paddle Incoming RSS feed items list';
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
  $handler->display->display_options['row_plugin'] = 'entity';
  $handler->display->display_options['row_options']['view_mode'] = 'title';
  /* Relationship: Feeds item: Owner Incoming RSS feed entity */
  $handler->display->display_options['relationships']['feed_entity_id']['id'] = 'feed_entity_id';
  $handler->display->display_options['relationships']['feed_entity_id']['table'] = 'feeds_item';
  $handler->display->display_options['relationships']['feed_entity_id']['field'] = 'feed_entity_id';
  $handler->display->display_options['relationships']['feed_entity_id']['required'] = TRUE;
  /* Field: RSS feed item: Rss feed item ID */
  $handler->display->display_options['fields']['feed_item_id']['id'] = 'feed_item_id';
  $handler->display->display_options['fields']['feed_item_id']['table'] = 'paddle_incoming_rss_feed_item';
  $handler->display->display_options['fields']['feed_item_id']['field'] = 'feed_item_id';
  /* Sort criterion: RSS feed item: Created date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'paddle_incoming_rss_feed_item';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Contextual filter: Feeds item: Feed entity id */
  $handler->display->display_options['arguments']['feed_entity_id']['id'] = 'feed_entity_id';
  $handler->display->display_options['arguments']['feed_entity_id']['table'] = 'feeds_item';
  $handler->display->display_options['arguments']['feed_entity_id']['field'] = 'feed_entity_id';
  $handler->display->display_options['arguments']['feed_entity_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['feed_entity_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['feed_entity_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['feed_entity_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['feed_entity_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['feed_entity_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['feed_entity_id']['validate']['type'] = 'numeric';
  $translatables['paddle_incoming_rss_feed_items_list'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Owner Incoming RSS feed entity'),
    t('Rss feed item ID'),
    t('.'),
    t(','),
    t('All'),
  );
  $export['paddle_incoming_rss_feed_items_list'] = $view;

  return $export;
}
