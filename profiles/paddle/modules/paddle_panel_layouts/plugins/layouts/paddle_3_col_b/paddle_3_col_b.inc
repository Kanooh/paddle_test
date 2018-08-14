<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Omicron'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_3_col_b.png',
  'theme' => 'paddle_3_col_b',
  'css' => 'paddle_3_col_b.css',
  'regions' => array(
    'top' => t('A'),
    'first_left' => t('B'),
    'second_middle' => t('C'),
    'third_right' => t('D'),
  ),
);
