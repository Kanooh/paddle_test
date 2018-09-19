<?php

/**
 * @file
 * Panels layout definition for the three column Dashboard layout.
 */

// Plugin definition.
$plugin = array(
  'title' => t('Three column Dashboard layout'),
  'category' => t('Columns: 3'),
  'icon' => 'paddle_three_column.png',
  'theme' => 'paddle_dashboard_paddle_dashboard_layout',
  'regions' => array(
    'left' => t('Left side'),
    'middle' => t('Middle column'),
    'right' => t('Right side'),
  ),
);
