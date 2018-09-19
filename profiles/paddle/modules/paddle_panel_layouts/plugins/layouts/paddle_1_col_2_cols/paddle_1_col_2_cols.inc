<?php
/**
 * @file
 * Declare the settings for the two rows omega layout.
 *
 * First row has 2 columns in a 6-6 division based on 12-grid.
 * Second row has one full size column.
 * Rows are repeated 4 times.
 */

/**
 * Plugin definition for for this two rows layout.
 */
$plugin = array(
  'title' => t('Omega'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_1_col_2_cols.png',
  'theme' => 'paddle_1_col_2_cols',
  'css' => 'paddle_1_col_2_cols.css',
  'regions' => array(
    'row_1_left' => t('A'),
    'row_1_right' => t('B'),
    'row_2_full' => t('C'),
    'row_3_left' => t('D'),
    'row_3_right' => t('E'),
    'row_4_full' => t('F'),
    'row_5_left' => t('G'),
    'row_5_right' => t('H'),
    'row_6_full' => t('I'),
    'row_7_left' => t('J'),
    'row_7_right' => t('K'),
    'row_8_full' => t('L'),
  ),
);
