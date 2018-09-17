<?php
/**
 * @file
 * Declare the settings for the 9/3 two column layout, with a bottom region.
 */

/**
 * Plugin definition for the 9/3 two column layout, with a bottom region.
 */
$plugin = array(
  'title' => t('Kappa'),
  'category' => t('Paddle Content Regions'),
  'theme' => 'paddle_2_col_9_3_bottom',
  'icon' => '',
  'css' => 'paddle_2_col_9_3_bottom.css',
  'regions' => array(
    'right' => t('Right'),
    'left' => t('Page content'),
    'bottom' => t('Bottom'),
  ),
);
