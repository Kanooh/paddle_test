<?php
/**
 * @file
 * Declare the settings for the single column layout.
 */

/**
 * Plugin definition for the single column layout.
 */
$plugin = array(
  'title' => t('Eta'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_no_column.png',
  'theme' => 'paddle_no_column',
  'css' => 'paddle_no_column.css',
  'regions' => array('middle' => t('A')),
);
