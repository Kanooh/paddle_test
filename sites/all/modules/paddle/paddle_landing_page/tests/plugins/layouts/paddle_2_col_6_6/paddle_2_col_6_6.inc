<?php
/**
 * @file
 * Declare the settings for the 6/6 two column layout.
 */

/**
 * Plugin definition for the 6/6 two column layout.
 */
$plugin = array(
  'title' => t('6/6 two column layout'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_6_6.png',
  'theme' => 'paddle_2_col_6_6',
  'css' => 'paddle_2_col_6_6.css',
  'regions' => array(
    'right' => t('right'),
    'left' => t('left'),
  ),
);
