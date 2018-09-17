<?php
/**
 * @file
 * Theme functions and template preprocess.
 */

/**
 * Theme function to render the vocabulary filter in a table.
 */
function theme_paddle_advanced_search_vocabulary_filter($variables) {
  $element = $variables['element'];

  $rows = array();
  foreach (element_children($element) as $key) {
    $row = array();

    // Render the checkbox first.
    $row[] = drupal_render($element[$key]['enabled']);

    // Render all the radio buttons in different columns.
    foreach (element_children($element[$key]['mode']) as $radio_key) {
      // Hide the title.
      $element[$key]['mode'][$radio_key]['#title_display'] = 'invisible';
      // Add the rendered element to the row.
      $row[] = drupal_render($element[$key]['mode'][$radio_key]);
    }

    $rows[] = array(
      'data' => $row,
      'data-term-id' => $element[$key]['#taxonomy_term']->tid,
    );
  }

  // The headers of the table consist in the already translated options values,
  // plus an additional explanation of the first column.
  $header = array_merge(array(t('Available terms')), $element['#option_labels']);

  $output = theme('table', array(
    'header' => $header,
    'rows' => $rows,
  ));

  return $output;
}

/**
 * Theme function to render the search results views style plugin.
 */
function theme_paddle_advanced_search_view_search_results(&$variables) {
if(!empty($variables) && !empty($variables['view'])){
  $view = $variables['view'];
  return theme('search_api_page_results', array(
    'index' => search_api_index_load('node_index'),
    'results' => $view->query->getSearchApiResults(),
    'view_mode' => 'search_api_page_result',
    'page' => search_api_page_load('search'),
  ));
}
  return FALSE;
}
