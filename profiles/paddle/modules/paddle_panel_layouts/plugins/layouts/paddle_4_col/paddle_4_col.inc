<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Sigma'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_4_col.png',
  'theme' => 'paddle_4_col',
  'css' => 'paddle_4_col.css',
  'regions' => array(
    'nested_top' => t('A'),
    'nested_left' => t('B'),
    'nested_right' => t('C'),
    'second_column' => t('D'),
    'third_column' => t('E'),
  ),
);
