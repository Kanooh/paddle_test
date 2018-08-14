<?php
/**
 * @file
 * Declare the settings for the two rows layout.
 * First row has 3 colunmns in a 4-4-4 division based on 12-grid
 * Second row has 2 colunmns in a 8-4 division  based on 12-grid
 */

/**
 * Plugin definition for for this two rows layout.
 */
$plugin = array(
  'title' => t('Mu'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_2_cols_3_cols_c.png',
  'theme' => 'paddle_2_cols_3_cols_c',
  'css' => 'paddle_2_cols_3_cols_c.css',
  'regions' => array(
    'row_1_left' => t('A'),
    'row_1_middle' => t('B'),
    'row_1_right' => t('C'),
    'row_2_left' => t('D'),
    'row_2_right' => t('E'),
  ),
);
