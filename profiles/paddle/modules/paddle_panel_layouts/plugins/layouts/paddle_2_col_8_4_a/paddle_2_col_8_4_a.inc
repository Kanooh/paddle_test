<?php
/**
 * @file
 * Declare the settings for the 8/4 two column layout in "a" variation.
 */

/**
 * Plugin definition for this 8/4 layout.
 */
$plugin = array(
  'title' => t('Rho'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_col_8_4_a.png',
  'theme' => 'paddle_2_col_8_4_a',
  'css' => 'paddle_2_col_8_4_a.css',
  'regions' => array(
    'row_1_full' => t('A'),
    'row_2_left' => t('B'),
    'row_2_right' => t('C'),
    'row_3_full' => t('D'),
    'row_4_left' => t('E'),
    'row_4_right' => t('F'),
    'row_5_full' => t('G'),
    'row_6_left' => t('H'),
    'row_6_right' => t('I'),
    'row_7_full' => t('J'),
    'row_8_left' => t('K'),
    'row_8_right' => t('L'),
    'right' => t('M'),
  ),
);
