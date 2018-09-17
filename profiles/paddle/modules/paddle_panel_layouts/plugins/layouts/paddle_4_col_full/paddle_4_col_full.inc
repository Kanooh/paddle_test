<?php
/**
 * @file
 * Declare the settings for the four column layout.
 */

/**
 * Plugin definition for the four column layout.
 */
$plugin = array(
  'title' => t('Iota'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_4_col_full.png',
  'theme' => 'paddle_4_col_full',
  'css' => 'paddle_4_col_full.css',
  'regions' => array(
    'column_one' => t('A'),
    'column_two' => t('B'),
    'column_three' => t('C'),
    'column_four' => t('D'),
  ),
);
