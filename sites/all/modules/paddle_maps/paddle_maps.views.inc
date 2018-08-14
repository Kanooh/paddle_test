<?php

/**
 * @file
 * Views related hooks.
 */

/**
 * Implements hook_views_plugins().
 */
function paddle_maps_views_plugins() {
  return array(
    'style' => array(
      'paddle_block_maps_search_results' => array(
        'title' => t('Search results'),
        'help' => t('Displays rows as search results.'),
        'handler' => 'paddle_maps_views_plugin_style_search_results',
        'theme' => 'paddle_maps_view_search_results',
        'theme path' => drupal_get_path('module', 'paddle_maps') . '/theme',
        'theme file' => 'theme.inc',
        'uses row plugin' => FALSE,
        'uses row class' => FALSE,
        'uses grouping' => FALSE,
        'uses options' => FALSE,
        'type' => 'normal',
      ),
    ),
  );
}

/**
 * Implements hook_views_pre_view().
 */
function paddle_maps_views_pre_view(&$view) {
  if ($view->name == 'paddle_maps' && $view->current_display == 'block_maps_text') {
    // Only show the empty message for the results list, not for the map.
    // Otherwise the message will show twice.
    $empty['empty'] = array(
      'id' => 'area',
      'table' => 'views',
      'field' => 'area',
      'empty' => TRUE,
      'content' => paddle_search_retrieve_no_search_results_message(),
      'format' => 'full_html',
      'tokenize' => 0,
    );

    $view->display_handler->set_option('empty', $empty);
  }
}
