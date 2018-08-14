<?php
/**
 * @file
 * Declare the settings for the four column layout.
 */

/**
 * Plugin definition for the four column layout.
 */
$plugin = array(
  'title' => t('Kappa'),
  'category' => t('Paddle Layouts'),
  'icon' => 'paddle_4_col_multiline.png',
  'theme' => 'paddle_4_col_multiline',
  'css' => 'paddle_4_col_multiline.css',
  'regions' => array(
    'nested_6_a' => t('A'),
    'nested_3_a' => t('B'),
    'nested_3_b' => t('C'),
    'nested_3_c' => t('D'),
    'nested_3_d' => t('E'),
    'nested_3_e' => t('F'),
    'nested_3_f' => t('G'),
    'nested_6_b' => t('H'),
    'nested_3_g' => t('I'),
    'nested_3_h' => t('J'),
    'full_bottom' => t('K'),
  ),
);
