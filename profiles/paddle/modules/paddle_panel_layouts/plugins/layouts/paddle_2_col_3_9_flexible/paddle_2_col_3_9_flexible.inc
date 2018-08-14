<?php
/**
 * @file
 * Declare the settings for the 3/9 two column layout.
 */

/**
 * Plugin definition for the 3/9 two column layout.
 */
$plugin = array(
  'title' => t('Alfa Flexible'),
  'category' => t('Columns: 2'),
  'icon' => 'paddle_2_col_3_9_flexible.png',
  'theme' => 'paddle_2_col_3_9_flexible',
  'css' => 'paddle_2_col_3_9_flexible.css',
  'regions' => array(
    'right' => t('B'),
    'left' => t('A'),
  ),
);
