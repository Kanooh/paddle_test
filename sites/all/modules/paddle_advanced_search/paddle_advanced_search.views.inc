<?php

/**
 * @file
 * Views related hooks.
 */

/**
 * Implements hook_views_plugins().
 */
function paddle_advanced_search_views_plugins() {
  return array(
    'style' => array(
      'paddle_advanced_search_results' => array(
        'title' => t('Search results'),
        'help' => t('Displays rows as search results.'),
        'handler' => 'paddle_advanced_search_views_plugin_style_search_results',
        'theme' => 'paddle_advanced_search_view_search_results',
        'theme path' => drupal_get_path('module', 'paddle_advanced_search') . '/theme',
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
 * Implements hook_preprocess_views_view().
 */
function paddle_advanced_search_preprocess_views_view(&$variables) {
  // First we check if we are situated in the admin view of the page
  // since the logic of retrieving the node object from the menu
  // is different.
  // @see paddle_content_manager_preprocess_page().
  if (path_is_admin(current_path())) {
    $menu_item = menu_get_item();

    if ($menu_item['path'] == 'admin/content_manager/node/%/view' && is_object($menu_item['page_arguments'][1])) {
      $node = workbench_moderation_node_current_load($menu_item['page_arguments'][1]->data);
    }
  }
  else {
    $node = menu_get_object();
  }

  if (!empty($node) && $node->type == 'paddle_advanced_search_page') {
    $node_wrapper = entity_metadata_wrapper('node', $node);

    if (!empty($node_wrapper->field_paddle_pager_bottom) && $node_wrapper->field_paddle_pager_bottom->value() !== NULL) {
      $variables['paddle_pager_bottom'] = $node_wrapper->field_paddle_pager_bottom->value();
    }
    else {
      // The pager is display by default at the bottom.
      $variables['paddle_pager_bottom'] = TRUE;
    }

    if (!empty($node_wrapper->field_paddle_pager_top)  && $node_wrapper->field_paddle_pager_top->value() !== NULL) {
      $variables['paddle_pager_top'] = $node_wrapper->field_paddle_pager_top->value();
    }
    else {
      $variables['paddle_pager_top'] = FALSE;
    }
  }
}

/**
 * Implements hook_views_pre_view().
 */
function paddle_advanced_search_views_pre_view(&$view) {
  if ($view->name != 'paddle_advanced_search') {
    return;
  }

  // Create a "Global: Textarea" view element.
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
