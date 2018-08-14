<?php
/**
 * @file
 * paddle_incoming_rss.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function paddle_incoming_rss_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'feeds_source_class';
  $strongarm->value = 'PaddleIncomingRssFeedsSource';
  $export['feeds_source_class'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_paddle_incoming_rss_feed_item__paddle_incoming_rss_feed_item';
  $strongarm->value = array(
    'view_modes' => array(),
    'extra_fields' => array(
      'form' => array(),
      'display' => array(
        'thumbnail' => array(
          'title' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'magazine' => array(
            'weight' => '0',
            'visible' => TRUE,
          ),
        ),
        'changed' => array(
          'title' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
          'magazine' => array(
            'weight' => '3',
            'visible' => FALSE,
          ),
        ),
        'created' => array(
          'magazine' => array(
            'weight' => '1',
            'visible' => TRUE,
          ),
          'title' => array(
            'weight' => '0',
            'visible' => FALSE,
          ),
        ),
      ),
    ),
  );
  $export['field_bundle_settings_paddle_incoming_rss_feed_item__paddle_incoming_rss_feed_item'] = $strongarm;

  return $export;
}
