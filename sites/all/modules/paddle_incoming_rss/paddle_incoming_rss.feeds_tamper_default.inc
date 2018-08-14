<?php
/**
 * @file
 * paddle_incoming_rss.feeds_tamper_default.inc
 */

/**
 * Implements hook_feeds_tamper_default().
 */
function paddle_incoming_rss_feeds_tamper_default() {
  $export = array();

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'paddle_incoming_rss-url-copy';
  $feeds_tamper->importer = 'paddle_incoming_rss';
  $feeds_tamper->source = 'url';
  $feeds_tamper->plugin_id = 'copy';
  $feeds_tamper->settings = array(
    'to_from' => 'to',
    'source' => 'Blank source 1',
  );
  $feeds_tamper->weight = 0;
  $feeds_tamper->description = 'Copy source value';
  $export['paddle_incoming_rss-url-copy'] = $feeds_tamper;

  $feeds_tamper = new stdClass();
  $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
  $feeds_tamper->api_version = 2;
  $feeds_tamper->id = 'paddle_incoming_rss-url-find_replace_regex';
  $feeds_tamper->importer = 'paddle_incoming_rss';
  $feeds_tamper->source = 'url';
  $feeds_tamper->plugin_id = 'find_replace_regex';
  $feeds_tamper->settings = array(
    'find' => '/.*\\:\\/\\/(?:www.)?([^\\/]+).*/',
    'replace' => '$1',
    'limit' => '',
    'real_limit' => -1,
  );
  $feeds_tamper->weight = 1;
  $feeds_tamper->description = 'Find replace REGEX';
  $export['paddle_incoming_rss-url-find_replace_regex'] = $feeds_tamper;

  return $export;
}
