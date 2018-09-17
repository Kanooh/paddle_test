<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout.
 */

/**
 * Plugin definition for the 9/3 two column layout.
 */
$plugin = array(
  'title' => t('9/3 two column layout'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_9_3.png',
  'theme' => 'paddle_2_col_9_3',
  'css' => 'paddle_2_col_9_3.css',
  'regions' => array(
    'right' => t('right'),
    'left' => t('left'),
  ),
);
