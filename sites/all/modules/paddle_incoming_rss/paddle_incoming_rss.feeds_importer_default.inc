<?php
/**
 * @file
 * paddle_incoming_rss.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_incoming_rss_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'paddle_incoming_rss';
  $feeds_importer->config = array(
    'name' => 'Paddle import RSS',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => FALSE,
        'use_pubsubhubbub' => FALSE,
        'designated_hub' => '',
        'request_timeout' => NULL,
        'auto_scheme' => 'http',
        'accept_invalid_cert' => FALSE,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsSyndicationParser',
      'config' => array(),
    ),
    'processor' => array(
      'plugin_key' => 'PaddleIncomingRssFeedItemProcessor',
      'config' => array(
        'values' => array(
          'title' => '',
        ),
        'authorize' => TRUE,
        'mappings' => array(
          0 => array(
            'source' => 'guid',
            'target' => 'guid',
            'unique' => 1,
          ),
          1 => array(
            'source' => 'title',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'description',
            'target' => 'field_feed_item_description',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'timestamp',
            'target' => 'created',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'media_thumbnail_url',
            'target' => 'thumbnail',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'media_content_url',
            'target' => 'thumbnail',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'url',
            'target' => 'source',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'Blank source 1',
            'target' => 'url',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '1',
        'update_non_existent' => 'skip',
        'input_format' => 'full_html',
        'skip_hash_check' => 0,
        'bundle' => 'paddle_incoming_rss_feed_item',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '1800',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 1,
  );
  $export['paddle_incoming_rss'] = $feeds_importer;

  return $export;
}
