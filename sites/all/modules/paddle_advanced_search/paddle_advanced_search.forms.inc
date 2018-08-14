<?php

/**
 * @file
 * Form callbacks for Paddle advanced search.
 */

/**
 * Form callback to launch a keyword search on advanced search node.
 *
 * @param array $form
 *   An associative array containing the structure of the form.
 * @param array $form_state
 *   A keyed array containing the current state of the form.
 * @param object $node
 *   The node this forms refers to.
 * @param array $conf
 *   The configuration of the pane, if present.
 * @param string $default_filter
 *   URL parameters to pass on as default filters for the advanced search page.
 *
 * @return array
 *   The final structure of the form.
 */
function paddle_advanced_search_form($form, &$form_state, $node, $conf, $default_filter = '') {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_advanced_search', 'paddle_advanced_search.forms');

  $form['search'] = array(
    '#type' => 'textfield',
    '#default_value' => isset($_GET['search']) ? $_GET['search'] : '',
  );

  // Make the form a GET form.
  $form_state['method'] = 'get';

  $form['#action'] = url('node/' . $node->nid);
  $form['#node'] = $node;

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
 * Implements hook_form_paddle_advanced_search_form_alter().
 *
 * Removes unwanted information from the GET parameters.
 *
 * @see views_form_views_exposed_form_alter()
 */
function paddle_advanced_search_form_paddle_advanced_search_form_alter(&$form, &$form_state) {
  $form['form_build_id']['#access'] = FALSE;
  $form['form_token']['#access'] = FALSE;
  $form['form_id']['#access'] = FALSE;
  $node = NULL;

  if (!empty($form['#node'])) {
    $node = $form['#node'];
  }

  if (!empty($_GET['f'])) {
    $params = '';
    foreach ($_GET['f'] as $key => $value) {
      $params .= 'f[' . $key . ']=' . $value . '&';
    }
    $params = substr($params, 0, -1);

    $_SESSION['paddle_advanced_search_params_' . $node->nid] = $params;
  }
  elseif (!isset($_GET['search'])) {
    unset($_SESSION['paddle_advanced_search_params_' . $node->nid]);
  }

  if (empty($default_filter) && !empty($_SESSION['paddle_advanced_search_params_' . $node->nid])) {
    $default_filter = $_SESSION['paddle_advanced_search_params_' . $node->nid];
  }

  // Extend the form if we have to pass along a default filter.
  if (!empty($default_filter)) {
    // Can't just append the default filter to $form['#action'] because per RFC
    // browsers are asked to overwrite the query part of the action parameter
    // of a GET form instead of appending to it.
    // Can't use drupal_parse_url('?' . $default_filter) because that converts
    // a query parameter key like 'f[0]' into an array which makes it very hard
    // to reassemble correctly into hidden form fields.
    $decoded_filter = rawurldecode($default_filter);
    $splitted_filter = explode('&', $decoded_filter);
    foreach ($splitted_filter as $filter) {
      $key_value = explode('=', $filter);
      if ($key_value[0] != 'search') {
        $form[$key_value[0]] = array(
          '#type' => 'hidden',
          '#value' => $key_value[1],
          '#weight' => -1,
        );
      }
    }
  }
}
