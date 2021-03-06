<?php
/**
 * @file
 * Declare the settings for the three column layout.
 */

/**
 * Plugin definition for the three column layout.
 */
$plugin = array(
  'title' => t('Theta'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_three_column.png',
  'theme' => 'paddle_three_column',
  'css' => 'paddle_three_column.css',
  'regions' => array(
    'left' => t('A'),
    'middle' => t('B'),
    'right' => t('C'),
  ),
);
