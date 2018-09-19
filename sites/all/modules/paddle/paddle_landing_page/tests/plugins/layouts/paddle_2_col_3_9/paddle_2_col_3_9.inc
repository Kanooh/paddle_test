<?php
/**
 * @file
 * Declare the settings for the 3/9 two column layout.
 */

/**
 * Plugin definition for the 3/9 two column layout.
 */
$plugin = array(
  'title' => t('3/9 two column layout'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_3_9.png',
  'theme' => 'paddle_2_col_3_9',
  'css' => 'paddle_2_col_3_9.css',
  'regions' => array(
    'right' => t('right'),
    'left' => t('left'),
  ),
);
