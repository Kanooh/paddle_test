<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('Nu'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_3_col_c.png',
  'theme' => 'paddle_3_col_c',
  'css' => 'paddle_3_col_c.css',
  'regions' => array(
    'top' => t('A'),
    '1_a' => t('B'),
    '2_a' => t('C'),
    '3_b' => t('D'),
    '4_b' => t('E'),
    '5_b' => t('F'),
  ),
);
