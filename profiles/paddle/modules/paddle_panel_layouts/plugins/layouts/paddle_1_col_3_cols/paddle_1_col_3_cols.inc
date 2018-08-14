<?php
/**
 * @file
 * Declare the settings for the two rows psi layout.
 *
 * First row has one full size column.
 * Second row has 3 columns in a 4-4-4 division based on 12-grid.
 * Rows are repeated 4 times.
 */

/**
 * Plugin definition for for this two rows layout.
 */
$plugin = array(
  'title' => t('Psi'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_1_col_3_cols.png',
  'theme' => 'paddle_1_col_3_cols',
  'css' => 'paddle_1_col_3_cols.css',
  'regions' => array(
    'row_1_full' => t('A'),
    'row_2_left' => t('B'),
    'row_2_center' => t('C'),
    'row_2_right' => t('D'),
    'row_3_full' => t('E'),
    'row_4_left' => t('F'),
    'row_4_center' => t('G'),
    'row_4_right' => t('H'),
    'row_5_full' => t('I'),
    'row_6_left' => t('J'),
    'row_6_center' => t('K'),
    'row_6_right' => t('L'),
    'row_7_full' => t('M'),
    'row_8_left' => t('N'),
    'row_8_center' => t('O'),
    'row_8_right' => t('P'),
  ),
);
