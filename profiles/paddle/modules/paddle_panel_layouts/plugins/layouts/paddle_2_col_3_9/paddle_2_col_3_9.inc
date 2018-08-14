<?php
/**
 * @file
 * Declare the settings for the 3/9 two column layout.
 */

/**
 * Plugin definition for the 3/9 two column layout.
 */
$plugin = array(
  'title' => t('Alfa'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_3_9.png',
  'theme' => 'paddle_2_col_3_9',
  'css' => 'paddle_2_col_3_9.css',
  'regions' => array(
    'right' => t('B'),
    'left' => t('A'),
  ),
);
