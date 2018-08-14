<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Lamda'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_9_3_d.png',
  'theme' => 'paddle_2_col_9_3_d',
  'css' => 'paddle_2_col_9_3_d.css',
  'regions' => array(
    'nested_top' => t('A'),
    'nested_left' => t('B'),
    'nested_right' => t('C'),
    'bottom' => t('D'),
    'right' => t('E'),
  ),
);
