<?php
/**
 * @file
 * paddle_search.pm_existing_pages.inc
 */

/**
 * Implements hook_pm_existing_pages_info().
 */
function paddle_search_pm_existing_pages_info() {
  $export = array();

  $pm_existing_page = new stdClass();
  $pm_existing_page->api_version = 1;
  $pm_existing_page->name = 'search_results';
  $pm_existing_page->label = 'Search results';
  $pm_existing_page->context = '';
  $pm_existing_page->paths = 'search';
  $export['search_results'] = $pm_existing_page;

  return $export;
}
