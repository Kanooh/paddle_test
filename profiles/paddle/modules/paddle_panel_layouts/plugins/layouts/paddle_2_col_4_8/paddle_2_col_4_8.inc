<?php
/**
 * @file
 * Declare the settings for the 4/8 two column layout.
 */

/**
 * Plugin definition for this 4/8 layout.
 */
$plugin = array(
  'title' => t('Tau'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_4_8.png',
  'theme' => 'paddle_2_col_4_8',
  'css' => 'paddle_2_col_4_8.css',
  'regions' => array(
    'left' => t('A'),
    'row_1_full' => t('B'),
    'row_2_left' => t('C'),
    'row_2_right' => t('D'),
    'row_3_full' => t('E'),
    'row_4_left' => t('F'),
    'row_4_right' => t('G'),
    'row_5_full' => t('H'),
    'row_6_left' => t('I'),
    'row_6_right' => t('J'),
    'row_7_full' => t('K'),
    'row_8_left' => t('L'),
    'row_8_right' => t('M'),
  ),
);
