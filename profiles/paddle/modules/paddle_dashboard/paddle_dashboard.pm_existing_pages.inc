<?php
/**
 * @file
 * paddle_dashboard.pm_existing_pages.inc
 */

/**
 * Implements hook_pm_existing_pages_info().
 */
function paddle_dashboard_pm_existing_pages_info() {
  $export = array();

  $pm_existing_page = new stdClass();
  $pm_existing_page->api_version = 1;
  $pm_existing_page->name = 'admin';
  $pm_existing_page->label = 'Admin';
  $pm_existing_page->context = '';
  $pm_existing_page->paths = 'admin';
  $export['admin'] = $pm_existing_page;

  return $export;
}
