<?php
/**
 * @file
 * Declare the settings for the two rows layout.
 * First row has 2 colunmns in a 8-4 division  based on 12-grid
 * Second row has 3 colunmns in a 4-4-4 division based on 12-grid
 * Third row has 1 colunmn
 */

/**
 * Plugin definition for for this two rows layout.
 */
$plugin = array(
  'title' => t('Xi'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_cols_3_cols_d.png',
  'theme' => 'paddle_2_cols_3_cols_d',
  'css' => 'paddle_2_cols_3_cols_d.css',
  'regions' => array(
    'row_1_left' => t('A'),
    'row_1_right' => t('B'),
    'row_2_left' => t('C'),
    'row_2_middle' => t('D'),
    'row_2_right' => t('E'),
    'bottom_row' => t('F'),
  ),
);
