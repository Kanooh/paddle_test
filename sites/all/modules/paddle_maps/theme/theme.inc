<?php

/**
 * @file
 * Theme functions and template preprocess.
 */

/**
 * Theme function to render the vocabulary filter in a table.
 */
function theme_paddle_maps_vocabulary_filter($variables) {
  $element = $variables['element'];

  $rows = array();
  foreach (element_children($element) as $key) {
    $row = array();

    // Render the checkbox first.
    $row[] = drupal_render($element[$key]['enabled']);

    // Render all the radio buttons in different columns.
    foreach (element_children($element[$key]['mode']) as $radio_key) {
      // Hide the title.
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
function theme_paddle_maps_view_search_results(&$variables) {
  if (!empty($variables) && !empty($variables['view'])) {
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

/**
 * Theming the marker.
 */
function theme_paddle_maps_marker_widget_multiple($variables) {
  $element = $variables['element'];

  // Special ID and classes for draggable tables.
  $weight_class = $element['#id'] . '-weight';
  $table_id = $element['#id'] . '-table';

  // Get our list of widgets in order (needed when the form comes back after
  // preview or failed validation).
  $widgets = array();
  foreach (element_children($element) as $key) {
    $widgets[] = &$element[$key];
  }
  usort($widgets, '_field_sort_items_value_helper');

  $rows = array();
  foreach ($widgets as $key => &$widget) {
    // Save the uploading row for last.
    if ($widget['#file'] == FALSE) {
      $widget['#title'] = $element['#file_upload_title'];
      $widget['#description'] = $element['#file_upload_description'];
    }
    else {
      $widget['#title'] = '';
    }

    $widget['#theme_wrappers'] = array();

    hide($widget['_weight']);
    hide($widget['preview']);
    hide($widget['term_selector']);
    hide($widget['is_default']);
    $information = drupal_render($widget);

    $widget['_weight']['#attributes']['class'] = array($weight_class);
    $weight = render($widget['_weight']);
    $marker_delta = $widget['_weight']['#default_value'];

    $row = array();
    $row[] = array('data' => render($widget['preview']), 'class' => array('marker-preview'));
    $row[] = array('data' => $information, 'class' => array('marker-file-upload'));
    $row[] = array('data' => render($widget['term_selector']), 'class' => array('marker-tid-selection'));
    $row[] = render($widget['is_default']);

    $rows[] = array(
      'data' => $row,
      'marker-row' => $marker_delta,
    );
  }

  $header = array(
    array(
      'class' => array(
        'preview-marker',
      ),
    ),
    '',
    '',
    array(
      'class' => array('is-default-marker'),
    ),
  );

  $output = '';
  $output = empty($rows) ? '' : theme(
    'table', array(
      'header' => $header,
      'rows' => $rows,
      'attributes' => array(
        'id' => $table_id,
        'class' => array('marker-config'),
      ),
    )
  );
  $output .= drupal_render_children($element);
  return $output;
}
