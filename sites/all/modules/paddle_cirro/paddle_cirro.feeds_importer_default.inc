<?php
/**
 * @file
 * paddle_cirro.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_cirro_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'paddle_cirro_feed';
  $feeds_importer->config = array(
    'name' => 'Paddle CIRRO feed',
    'description' => 'Imports resources from the CIRRO API and creates CIRRO pages from them',
    'fetcher' => array(
      'plugin_key' => 'FeedsCirroResourceFetcher',
      'config' => array(
        'auto_detect_feeds' => 1,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
        'auto_scheme' => 'https://www.cirro.zorg-en-gezondheid.be/api/v1/resources',
        'accept_invalid_cert' => 0,
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'guid' => array(
            'name' => 'GUID',
            'value' => 'Guid',
            'debug' => 0,
            'weight' => '1',
          ),
          'name' => array(
            'name' => 'Name',
            'value' => 'Name',
            'debug' => 0,
            'weight' => '2',
          ),
          'description' => array(
            'name' => 'Description',
            'value' => 'Description',
            'debug' => 0,
            'weight' => '3',
          ),
          'contactinfo' => array(
            'name' => 'Contactinfo',
            'value' => 'Contactinfo',
            'debug' => 0,
            'weight' => '4',
          ),
          'documents' => array(
            'name' => 'Documents',
            'value' => 'Documents',
            'debug' => 0,
            'weight' => '5',
          ),
          'links' => array(
            'name' => 'Links',
            'value' => 'Links',
            'debug' => 0,
            'weight' => '6',
          ),
          'methodiek' => array(
            'name' => 'Methodiek',
            'value' => 'Methodiek',
            'debug' => 0,
            'weight' => '7',
          ),
          'childresources' => array(
            'name' => 'ChildResources',
            'value' => 'ChildResources',
            'debug' => 0,
            'weight' => '8',
          ),
          'actionstrategies' => array(
            'name' => 'ActionStrategies',
            'value' => 'ActionStrategies',
            'debug' => 0,
            'weight' => '9',
          ),
          'policythemes' => array(
            'name' => 'PolicyThemes',
            'value' => 'PolicyThemes',
            'debug' => 0,
            'weight' => '10',
          ),
          'settings' => array(
            'name' => 'Settings',
            'value' => 'Settings',
            'debug' => 0,
            'weight' => '11',
          ),
        ),
        'context' => array(
          'value' => '$.*',
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
        'author' => 1,
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'guid',
            'target' => 'guid',
            'unique' => 1,
          ),
          1 => array(
            'source' => 'name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'description',
            'target' => 'body',
            'format' => 'full_html',
          ),
          3 => array(
            'source' => 'contactinfo',
            'target' => 'field_paddle_cirro_contact_info',
            'format' => 'plain_text',
          ),
          4 => array(
            'source' => 'methodiek',
            'target' => 'field_paddle_cirro_methodology',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'paddle_cirro_page',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '0',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 1,
  );
  $export['paddle_cirro_feed'] = $feeds_importer;

  return $export;
}
