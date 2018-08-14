<?php

/**
 * @file
 * Provides a label token for Paddle Content Regions.
 */

/**
 * Implements hook_tokens().
 */
function paddle_content_region_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();

  if ($type == 'paddle_content_region' && !empty($data['paddle_content_region'])) {
    foreach ($tokens as $name => $original) {
      if ($name == 'composed-label') {
        $replacements[$original] = paddle_content_region_label('paddle_content_region', $data['paddle_content_region']);
      }
    }
  }

  return $replacements;
}
