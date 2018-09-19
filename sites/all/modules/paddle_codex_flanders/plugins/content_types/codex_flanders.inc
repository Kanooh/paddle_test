<?php
/**
 * @file
 * CTools content type plugin to embed codex Flanders.
 */

$plugin = array(
  'title' => t('Codex Flanders'),
  'no title override' => TRUE,
  'description' => t('Add codices'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle codex Flanders'), -10),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_codex_flanders_codex_flanders_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $output = '';

  // Get the active version for the given article and render it.
  if (!empty($conf['codices'])) {
    foreach ($conf['codices'] as $key => $codex) {
      $parsed_url = drupal_parse_url($codex['url']);
      $aid = $parsed_url['query']['AID'];

      $response = paddle_codex_flanders_get_article_data($aid);
      if (is_array($response)) {
        $response['name'] = $codex['name'];
        $response['url'] = $codex['url'];
        $codices[] = theme('paddle_codex_flanders_codex', array('codex' => $response));
      }
      elseif ($response === CODEX_NO_ACTIVE_VERSION && user_is_logged_in()) {
        $codices[] = t('No active version for article with ID: !id has been found.', array('!id' => $aid));
      }
      elseif ($response === CODEX_NO_API_CONNECTION) {
        $codices[] = t('The Codex Flanders API is temporarily unavailable.');
      }
    }

    $output = theme('item_list', array('items' => $codices));
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_codex_flanders_codex_flanders_content_type_edit_form($form, &$form_state) {
  if (empty($form_state['conf']['codices'])) {
    // Add an empty codex to the form configuration.
    $form_state['conf']['codices'][1]['name'] = '';
    $form_state['conf']['codices'][1]['url'] = '';
  }

  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['section_body']['codices'] = array(
    '#type' => 'container',
    '#tree' => TRUE,
    '#prefix' => '<div id="codices">',
    '#suffix' => '</div>',
  );

  foreach ($conf['codices'] as $key => $codex) {
    $form['section_body']['codices'][$key] = array(
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => array('class' => array('codex')),
    );

    $form['section_body']['codices'][$key]['remove'] = array(
      '#type' => 'submit',
      '#value' => t('Remove'),
      '#name' => "remove_codex_$key",
      '#submit' => array('paddle_codex_flanders_codex_flanders_remove_codex'),
      '#ajax' => array(
        'callback' => 'paddle_codex_flanders_codex_flanders_ajax_add_remove_codex',
        'wrapper' => 'codices',
      ),
      '#limit_validation_errors' => array(),
      '#attributes' => array(
        'class' => array('remove'),
        'title' => t('Remove codex'),
      ),
    );

    // Add the name field.
    $form['section_body']['codices'][$key]['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => isset($conf['codices'][$key]['name']) ? $conf['codices'][$key]['name'] : '',
      '#size' => 60,
    );

    // The field for the link to the article.
    $form['section_body']['codices'][$key]['url'] = array(
      '#type' => 'textfield',
      '#title' => t('Codex Flanders link'),
      '#default_value' => isset($conf['codices'][$key]['url']) ? $conf['codices'][$key]['url'] : '',
      '#size' => 60,
      '#required' => TRUE,
    );
  }

  $form['section_body']['add_codex'] = array(
    '#type' => 'submit',
    '#value' => t('Add another codex'),
    '#submit' => array('paddle_codex_flanders_codex_flanders_add_more_add_codex'),
    '#ajax' => array(
      'callback' => 'paddle_codex_flanders_codex_flanders_ajax_add_remove_codex',
      'wrapper' => 'codices',
    ),
    '#limit_validation_errors' => array(),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_codex_flanders', 'plugins/content_types/codex_flanders');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_codex_flanders_codex_flanders_content_type_edit_form_validate($form, &$form_state) {
  foreach ($form_state['values']['codices'] as $key => $codex) {
    if (valid_url($codex['url'], TRUE)) {
      $parsed_url = drupal_parse_url($codex['url']);

      if (empty($parsed_url['query']['AID']) || is_int($parsed_url['query']['AID'])) {
        form_set_error('codices][' . $key . '][url', 'The given codex link does not contain an article ID.');
      }
    }
    else {
      form_set_error('codices][' . $key . '][url', 'The given url is not a valid one.');
    }
  }

  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_codex_flanders_codex_flanders_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Keep the AID's for easier search of panes during cron.
  foreach ($form_state['values']['codices'] as $key => $codex) {
    $parsed_url = drupal_parse_url($codex['url']);
    // Pun intended.
    $form_state['conf']['aids'][$parsed_url['query']['AID']] = $parsed_url['query']['AID'];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}

/**
 * Selects and returns the codices wrapper with the codices in it.
 */
function paddle_codex_flanders_codex_flanders_ajax_add_remove_codex($form, $form_state) {
  return $form['pane_settings']['section_body']['codices'];
}

/**
 * Submit handler for the "add-one-more" button.
 *
 * Increments the max counter and causes a rebuild.
 */
function paddle_codex_flanders_codex_flanders_add_more_add_codex($form, &$form_state) {
  // Add the empty codex to the form configuration.
  $form_state['conf']['codices'][] = array(
    'name' => '',
    'url' => '',
  );
  $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for the "Remove" button.
 */
function paddle_codex_flanders_codex_flanders_remove_codex($form, &$form_state) {
  // Remove the codex.
  $trigger = $form_state['triggering_element']['#parents'];
  unset($form_state['conf']['codices'][$trigger[1]]);
  unset($form_state['input']['codices'][$trigger[1]]);
  $form_state['rebuild'] = TRUE;
}
