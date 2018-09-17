<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Digamma'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_9_3_c.png',
  'theme' => 'paddle_2_col_9_3_c',
  'css' => 'paddle_2_col_9_3_c.css',
  'regions' => array(
    'nested_top' => t('A'),
    'nested_left' => t('B'),
    'nested_middle' => t('C'),
    'nested_right' => t('D'),
    'nested_bottom' => t('E'),
    'right' => t('F'),
  ),
);
