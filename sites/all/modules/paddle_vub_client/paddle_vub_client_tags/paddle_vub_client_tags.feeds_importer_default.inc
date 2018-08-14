<?php
/**
 * @file
 * paddle_vub_client_tags.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_vub_client_tags_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'vub_tags_importer';
  $feeds_importer->config = array(
    'name' => 'vub_tags_importer',
    'description' => 'Imports news item tags from the VUB server',
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
          'name' => array(
            'name' => 'name',
            'value' => 'name',
            'debug' => 0,
            'weight' => '1',
          ),
          'weight' => array(
            'name' => 'weight',
            'value' => 'weight',
            'debug' => 0,
            'weight' => '2',
          ),
          'tid' => array(
            'name' => 'tid',
            'value' => 'tid',
            'debug' => 0,
            'weight' => '3',
          ),
          'description' => array(
            'name' => 'description',
            'value' => 'description',
            'debug' => 0,
            'weight' => '4',
          ),
          'language' => array(
            'name' => 'language',
            'value' => 'language',
            'debug' => 0,
            'weight' => '5',
          ),
          'parent_tid' => array(
            'name' => 'parent_tid',
            'value' => 'parent[0][id]',
            'debug' => 0,
            'weight' => '6',
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
      'plugin_key' => 'FeedsTermProcessor',
      'config' => array(
        'vocabulary' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'tid',
            'target' => 'guid',
            'unique' => 1,
            'language' => 'und',
          ),
          1 => array(
            'source' => 'name',
            'target' => 'name',
            'unique' => FALSE,
            'language' => 'und',
          ),
          2 => array(
            'source' => 'weight',
            'target' => 'weight',
            'unique' => FALSE,
            'language' => 'und',
          ),
          3 => array(
            'source' => 'description',
            'target' => 'description',
            'unique' => FALSE,
            'language' => 'und',
          ),
          4 => array(
            'source' => 'tid',
            'target' => 'field_vub_server_tid',
            'unique' => FALSE,
            'language' => 'und',
          ),
          5 => array(
            'source' => 'language',
            'target' => 'language',
            'unique' => FALSE,
            'language' => 'und',
          ),
          6 => array(
            'source' => 'parent_tid',
            'target' => 'parentguid',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '1',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'vub_tags',
        'insert_new' => '1',
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
  $export['vub_tags_importer'] = $feeds_importer;

  return $export;
}
