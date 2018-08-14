<?php

/**
 * @file
 * Provides a label token for Paddle Mega Dropdowns.
 */

/**
 * Implements hook_tokens().
 */
function paddle_mega_dropdown_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();

  if ($type == 'paddle_mega_dropdown' && !empty($data['paddle_mega_dropdown'])) {
    foreach ($tokens as $name => $original) {
      if ($name == 'composed-label') {
        $replacements[$original] = paddle_mega_dropdown_label($data['paddle_mega_dropdown'], 'paddle_mega_dropdown');
      }
    }
  }

  return $replacements;
}
