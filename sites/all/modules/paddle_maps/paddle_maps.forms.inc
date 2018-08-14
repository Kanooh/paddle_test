<?php

/**
 * @file
 * Form callbacks for Paddle advanced search.
 */

/**
 * Form callback to launch a keyword search on advanced search node.
 *
 * @param object $node
 *   The node this forms refers to.
 *
 * @return array
 *   The final structure of the form.
 */
function paddle_maps_form($form, &$form_state, $node, $conf) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_maps', 'paddle_maps.forms');

  // Add the Panel Title and Body prefix and suffix.
  // Retrieve if the top section text should be displayed as <h2> or <div>.
  if (variable_get('paddle_style_display_pane_top_as_h2', FALSE)) {
    $form['#prefix'] = '<div class="pane-section-top"><h2>' . t('Search') . '</h2></div><div class="pane-section-body">';
  } else {
    $form['#prefix'] = '<div class="pane-section-top">' . t('Search') . '</div><div class="pane-section-body">';
  }
  $form['#suffix'] = '</div>';

  $form['search'] = array(
    '#type' => 'textfield',
    '#default_value' => isset($_GET['search']) ? $_GET['search'] : '',
  );

  // Make the form a GET form.
  $form_state['method'] = 'get';
  $form['#action'] = url('node/' . $node->nid);

  // Use the custom button text, if available.
  if (isset($conf['use_default_btn_text']) && !$conf['use_default_btn_text'] && isset($conf['custom_btn_text']) && strlen($conf['custom_btn_text'])) {
    $submit_text = $conf['custom_btn_text'];
  }
  else {
    $submit_text = t('Search');
  }

  $form['actions'] = array('#type' => 'actions');
  // Avoid using a name on the button, so it doesn't clutter the GET parameters
  // on submit.
  // @see views_exposed_form()
  $form['actions']['submit'] = array(
    '#name' => '',
    '#type' => 'submit',
    '#value' => $submit_text,
    '#id' => drupal_html_id('edit-submit'),
  );

  return $form;
}

/**
 * Implements hook_form_paddle_maps_form_alter().
 *
 * Removes unwanted information from the GET parameters.
 *
 * @see views_form_views_exposed_form_alter()
 */
function paddle_maps_form_paddle_maps_form_alter(&$form, &$form_state) {
  $form['form_build_id']['#access'] = FALSE;
  $form['form_token']['#access'] = FALSE;
  $form['form_id']['#access'] = FALSE;
}
