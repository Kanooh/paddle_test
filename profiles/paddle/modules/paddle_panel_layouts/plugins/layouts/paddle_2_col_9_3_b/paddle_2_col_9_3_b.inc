<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Epsilon'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_9_3_b.png',
  'theme' => 'paddle_2_col_9_3_b',
  'css' => 'paddle_2_col_9_3_b.css',
  'regions' => array(
    'nested_top' => t('A'),
    'nested_left' => t('B'),
    'nested_right' => t('C'),
    'nested_bottom' => t('D'),
    'right' => t('E'),
  ),
);
