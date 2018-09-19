<?php
/**
 * @file
 * Theme functions and template preprocess.
 */

/**
 * Theme function to render sortable atom fields in a table.
 */
function theme_paddle_scald_draggable_atoms($variables) {
  $element = $variables['element'];

  $rows = array();
  foreach (element_children($element, TRUE) as $child) {
    $element[$child]['weight']['#attributes']['class'][] = 'atom-weight';

    $rows[] = array(
      'data' => array(
        drupal_render($element[$child]['atom']),
        drupal_render($element[$child]['weight']),
        drupal_render($element[$child]['remove']),
      ),
      'class' => array('draggable'),
    );
  }

  $table_id = drupal_html_id('paddle-scald-draggable-atoms');
  $output = theme('table', array(
    'rows' => $rows,
    'attributes' => array('id' => $table_id),
  ));

  // Render all the remaining elements.
  $output .= drupal_render_children($element);

  drupal_add_tabledrag($table_id, 'order', 'sibling', 'atom-weight');

  return $output;
}
