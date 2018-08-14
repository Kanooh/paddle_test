<?php
/**
 * @file
 * paddle_search.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_search_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pm_existing_pages_disabled_search_results';
  $strongarm->value = FALSE;
  $export['pm_existing_pages_disabled_search_results'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'search_api_attachments_extracting_servlet_path';
  $strongarm->value = 'update/extract';
  $export['search_api_attachments_extracting_servlet_path'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'search_api_attachments_extract_using';
  $strongarm->value = 'solr';
  $export['search_api_attachments_extract_using'] = $strongarm;

  return $export;
}
