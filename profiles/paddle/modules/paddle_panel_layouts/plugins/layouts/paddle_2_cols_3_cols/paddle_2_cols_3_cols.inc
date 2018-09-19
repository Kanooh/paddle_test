<?php
/**
 * @file
 * Declare the settings for the two rows layout.
 * First row has 2 colunmns in a 8-4 division  based on 12-grid
 * Second row has 3 colunmns in a 4-4-4 division based on 12-grid
 */

/**
 * Plugin definition for for this two rows layout.
 */
$plugin = array(
  'title' => t('Zeta'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_cols_3_cols.png',
  'theme' => 'paddle_2_cols_3_cols',
  'css' => 'paddle_2_cols_3_cols.css',
  'regions' => array(
    'row_1_left' => t('A'),
    'row_1_right' => t('B'),
    'row_2_left' => t('C'),
    'row_2_middle' => t('D'),
    'row_2_right' => t('E'),
  ),
);
