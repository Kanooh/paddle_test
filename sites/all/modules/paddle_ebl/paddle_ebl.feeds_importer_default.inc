<?php
/**
 * @file
 * paddle_ebl.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function paddle_ebl_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'paddle_ebl_publication_importer';
  $feeds_importer->config = array(
    'name' => 'EBL Publication Importer',
    'description' => 'Imports publications into the environment as EBL pages.',
    'fetcher' => array(
      'plugin_key' => 'FeedsEBLPublicationFetcher',
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
      'plugin_key' => 'FeedsExJsonPath',
      'config' => array(
        'sources' => array(
          'logical_id' => array(
            'name' => 'logical_id',
            'value' => 'logical_id',
            'debug' => 0,
            'weight' => '1',
          ),
          'title' => array(
            'name' => 'title',
            'value' => 'title',
            'debug' => 0,
            'weight' => '2',
          ),
          'abstract' => array(
            'name' => 'abstract',
            'value' => 'abstract',
            'debug' => 0,
            'weight' => '3',
          ),
          'subtitle' => array(
            'name' => 'subtitle',
            'value' => 'subtitle',
            'debug' => 0,
            'weight' => '4',
          ),
          'publisher' => array(
            'name' => 'publisher',
            'value' => 'publisher',
            'debug' => 0,
            'weight' => '5',
          ),
          'language' => array(
            'name' => 'language',
            'value' => 'language',
            'debug' => 0,
            'weight' => '7',
          ),
          'type' => array(
            'name' => 'type',
            'value' => 'type',
            'debug' => 0,
            'weight' => '8',
          ),
          'covers' => array(
            'name' => 'covers',
            'value' => 'covers',
            'debug' => 0,
            'weight' => '9',
          ),
          'documents' => array(
            'name' => 'documents',
            'value' => 'documents',
            'debug' => 0,
            'weight' => '9',
          ),
          'external_urls' => array(
            'name' => 'external_urls',
            'value' => 'external_urls',
            'debug' => 0,
            'weight' => '10',
          ),
          'themes' => array(
            'name' => 'themes',
            'value' => 'themes',
            'debug' => 0,
            'weight' => '11',
          ),
          'publication_date' => array(
            'name' => 'publication_date',
            'value' => 'publication_date',
            'debug' => 0,
            'weight' => '12',
          ),
          'statuses' => array(
            'name' => 'statuses',
            'value' => 'statuses',
            'debug' => 0,
            'weight' => '13',
          ),
          'series' => array(
            'name' => 'series',
            'value' => 'serie',
            'debug' => 0,
            'weight' => '14',
          ),
          'authors' => array(
            'name' => 'authors',
            'value' => 'authors',
            'debug' => 0,
            'weight' => '15',
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
        'author' => '1',
        'authorize' => 0,
        'mappings' => array(
          0 => array(
            'source' => 'logical_id',
            'target' => 'guid',
            'unique' => 1,
          ),
          1 => array(
            'source' => 'abstract',
            'target' => 'body',
            'format' => 'full_html',
          ),
          2 => array(
            'source' => 'title',
            'target' => 'title',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'publisher',
            'target' => 'field_paddle_ebl_publisher',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'themes',
            'target' => 'field_paddle_ebl_themes',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'type',
            'target' => 'field_paddle_ebl_document_type',
            'term_search' => '0',
            'autocreate' => 1,
          ),
          6 => array(
            'source' => 'language',
            'target' => 'field_paddle_ebl_language',
            'term_search' => '0',
            'autocreate' => 1,
          ),
          7 => array(
            'source' => 'subtitle',
            'target' => 'field_paddle_ebl_subtitle',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'publication_date',
            'target' => 'field_paddle_ebl_pub_date:start',
            'unique' => FALSE,
          ),
          9 => array(
            'source' => 'authors',
            'target' => 'field_paddle_ebl_authors',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'series',
            'target' => 'field_paddle_ebl_series',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '2',
        'update_non_existent' => 'delete',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'paddle_ebl_page',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '0',
    'expire_period' => 3600,
    'import_on_create' => 0,
    'process_in_background' => 1,
  );
  $export['paddle_ebl_publication_importer'] = $feeds_importer;

  return $export;
}
