<?php
/**
 * @file
 * paddle_vub_client.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_vub_client_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'vub_client_news_importer';
  $feeds_importer->config = array(
    'name' => 'vub_client_news_importer',
    'description' => 'Imports news items from the VUB server',
    'fetcher' => array(
      'plugin_key' => 'VUBFeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => FALSE,
        'use_pubsubhubbub' => FALSE,
        'designated_hub' => '',
        'request_timeout' => NULL,
        'auto_scheme' => 'http',
        'accept_invalid_cert' => FALSE,
        'cache_http_result' => TRUE,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'body' => array(
            'name' => 'body',
            'value' => 'body[value]',
            'debug' => 0,
            'weight' => '0',
          ),
          'summary' => array(
            'name' => 'summary',
            'value' => 'body[summary]',
            'debug' => 0,
            'weight' => '1',
          ),
          'title' => array(
            'name' => 'title',
            'value' => 'title',
            'debug' => 0,
            'weight' => '2',
          ),
          'nid' => array(
            'name' => 'nid',
            'value' => 'nid',
            'debug' => 0,
            'weight' => '3',
          ),
          'language' => array(
            'name' => 'language',
            'value' => 'language',
            'debug' => 0,
            'weight' => '4',
          ),
          'field_vub_tags' => array(
            'name' => 'field_vub_tags',
            'value' => 'field_vub_tags',
            'debug' => 0,
            'weight' => '5',
          ),
          'field_paddle_featured_image' => array(
            'name' => 'field_paddle_featured_image',
            'value' => 'field_paddle_featured_image',
            'debug' => 0,
            'weight' => '6',
          ),
          'field_paddle_news_image_position' => array(
            'name' => 'field_paddle_news_image_position',
            'value' => 'field_paddle_news_image_position',
            'debug' => 0,
            'weight' => '7',
          ),
          'tnid' => array(
            'name' => 'tnid',
            'value' => 'source[id]',
            'debug' => 0,
            'weight' => '8',
          ),
          'field_vub_force_publish' => array(
            'name' => 'field_vub_force_publish',
            'value' => 'field_vub_force_publish',
            'debug' => 0,
            'weight' => '9',
          ),
        ),
        'context' => array(
          'value' => '$.list.*',
        ),
        'display_errors' => 0,
        'source_encoding' => array(
          0 => 'auto',
        ),
        'debug_mode' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => 0,
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'body',
            'target' => 'body',
            'format' => 'full_html',
            'language' => 'und',
          ),
          1 => array(
            'source' => 'summary',
            'target' => 'body:summary',
            'unique' => FALSE,
            'language' => 'und',
          ),
          2 => array(
            'source' => 'title',
            'target' => 'title',
            'unique' => FALSE,
            'language' => 'und',
          ),
          3 => array(
            'source' => 'language',
            'target' => 'language',
            'unique' => FALSE,
            'language' => 'und',
          ),
          4 => array(
            'source' => 'nid',
            'target' => 'field_vub_server_nid',
            'unique' => 1,
            'language' => 'und',
          ),
          5 => array(
            'source' => 'field_paddle_news_image_position',
            'target' => 'field_paddle_news_image_position',
            'unique' => FALSE,
            'language' => 'und',
          ),
          6 => array(
            'source' => 'field_vub_force_publish',
            'target' => 'field_vub_force_publish',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'skip',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'news_item',
        'insert_new' => 1,
        'language' => 'und',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '0',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 1,
  );
  $export['vub_client_news_importer'] = $feeds_importer;

  return $export;
}
