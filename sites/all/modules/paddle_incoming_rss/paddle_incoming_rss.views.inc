<?php

/**
 * @file
 * Views related hooks.
 */

/**
 * Implements hook_views_data_alter().
 */
function paddle_incoming_rss_views_data_alter(&$data) {
  if (!isset($data['feeds_item'])) {
    return;
  }

  $data['feeds_item']['feed_entity_id'] = array(
    'title' => t('Feed entity id'),
    'help' => t('The entity id of the owner Incoming RSS Feed entity if available.'),
    'real field' => 'feed_nid',
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
      'allow empty' => TRUE,
      'help' => t('Filter on Feed Items by the feed they were generated from, using the id of the Incoming RSS Feed entity.'),
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
      'numeric' => TRUE,
      'validate type' => 'numeric',
      'help' => t('Argument on Feed Items by the feed they were generated from, using the id of Incoming RSS entity.'),
    ),
    'sort' => array(
      'help' => t('Sort Feed Items by the feed they were generated from, using the id of the Incoming RSS entity.'),
    ),
    'relationship' => array(
      'title' => t('Owner Incoming RSS feed entity'),
      'help' => t('Relate a feed item to its owner Incoming RSS Feed entity if available.'),
      'label' => t('Owner Incoming RSS feed entity'),
      'base' => 'paddle_incoming_rss_feed',
      'base field' => 'feed_id',
    ),
  );
}
