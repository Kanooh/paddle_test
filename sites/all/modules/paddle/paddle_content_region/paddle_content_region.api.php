<?php

/**
 * @file
 * Hooks provided by the Paddle content region module.
 */

/**
 * Adds extra regions to the list of content regions per content type.
 *
 * @param string $content_type
 *   The content type which we require the extra regions from.
 *
 * @return array
 *   An array which contains the region, keyed by region ID with
 *   a label as value.
 */
function hook_paddle_content_region_extra_content_regions($content_type) {
  $regions = array();

  // Add regions to the array based on node type.
  if ($content_type == 'awesome_page') {
    $regions = array(
      'awesome_region' => t('Awesome region'),
    );
  }

  // Otherwise, return an empty array!
  return $regions;
}
