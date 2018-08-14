<?php

$plugin = array(
  'title' => t('UiTdatabank Pane'),
  'no title override' => TRUE,
  'description' => t('Add an UiTdatabank pane.'),
  'category' => t('Paddle Content Manager'),
  'paddle_panes sections' => TRUE,
  'defaults' => array(
    'selection_type' => 'search',
  ),
);

/**
 * Render callback for the UiTdatabank pane.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $callback = 'paddle_cultuurnet_uitdatabank_pane_content_type_get_content_' . $conf['selection_type'];
  $output = $callback($conf);

  //Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($output, $conf, $context);

  $block->content = $output;
  return $block;
}

/**
 * Retrieves the block content when the search selection type has been selected.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return string
 *   The rendered search block.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_get_content_search($conf) {
  $search_block = module_invoke('culturefeed_agenda', 'block_view', 'agenda-search-block');
  return render($search_block['content']);
}

/**
 * Retrieves the block content when the spotlight selection type has been selected.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return string
 *   The rendered spotlight block.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_get_content_spotlight($conf) {
  $variables = array();
  $template = 'paddle_cultuurnet_spotlight';
  $event_title = $conf["event"];
  $uitdatabank = new \Drupal\paddle_cultuurnet\UitDataBank();
  $event = $uitdatabank->paddle_cultuurnet_load_event_by_title($event_title);

  if (isset($event) && $event !== FALSE) {
    $variables = $uitdatabank->paddle_cultuurnet_prepare_event_for_spotlight($event_title, $event);
  }

  drupal_add_css(drupal_get_path('module', 'paddle_cultuurnet') . '/css/paddle_cultuurnet.spotlight.css');

  // Allow other modules to alter the fields.
  drupal_alter($template, $variables);
  return theme($template, $variables);
}

/**
 * Retrieves the block content when the list selection type has been selected.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return string
 *   The rendered list block.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_get_content_list($conf) {
  $variables = array();
  $template = '';

  $tag = $conf["tag"];
  $limit = $conf["amount"];

  $uitdatabank = new \Drupal\paddle_cultuurnet\UitDataBank();
  $events = $uitdatabank->paddle_cultuurnet_get_events_by_tag($tag, $limit);

  if (empty($events)) {
    return t('There are no events for the tag :tag', array(':tag' => $tag));
  }

  $view_mode = $conf['view_mode'];
  $variables['events'] = $uitdatabank->paddle_cultuurnet_prepare_events_for_list($events, $view_mode);

  foreach ($variables['events'] as $row_event) {
    $title = $row_event['title'];
    $event = $uitdatabank->paddle_cultuurnet_load_event_by_title($title);
    $variables['events']['spotlight'][] = $uitdatabank->paddle_cultuurnet_prepare_event_for_spotlight($title, $event);
  }

  switch ($view_mode) {
    case 'titles':
      $template = 'paddle_cultuurnet_list_titles';
      break;

    case 'summaries':
      $template = 'paddle_cultuurnet_list_summaries';
      break;

    case 'spotlight':
      $template = 'paddle_cultuurnet_list_spotlight';
      break;
  }
  drupal_add_css(drupal_get_path('module', 'paddle_cultuurnet') . '/css/paddle_cultuurnet.spotlight.css');
  drupal_add_css(drupal_get_path('module', 'paddle_cultuurnet') . '/css/paddle_cultuurnet.list.css');

  drupal_alter($template, $variables);
  return theme($template, $variables);
}

/**
 * Edit form callback for the content type.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form($form, &$form_state) {
  $api_key = variable_get('culturefeed_search_api_application_key', '');
  $shared_secret = variable_get('culturefeed_search_api_shared_secret', '');

  if (!empty($api_key) && !empty($shared_secret)) {
    $conf = $form_state['conf'];

    // Existing pane don't take into account pane defaults anymore.
    $plugin = $form_state['plugin'];
    $conf += $plugin['defaults'];

    $form['section_body'] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#weight' => -50,
      '#attributes' => array(
        'id' => drupal_html_id('pane-section-body'),
        'class' => array(drupal_html_class('pane-section-body')),
      ),
    );

  $form['section_body']['selection_type'] = array(
    '#type' => 'radios',
    '#title' => t('Selection type'),
    '#options' => array(
      'search' => t('Search field'),
      'spotlight' => t('In the spotlight'),
      'list' => t('List'),
    ),
    '#default_value' => $conf['selection_type'],
    '#ajax' => array(
      'callback' => 'paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_selection_type_ajax_callback',
      'wrapper' => $form['section_body']['#attributes']['id'],
    ),
  );

    // Render the form for the chosen selection type.
    $type = !empty($form_state['values']['selection_type']) ? $form_state['values']['selection_type'] : $conf['selection_type'];
    $callback = 'paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_' . $type;
    $form = $callback($form, $form_state);

    // Include the form elements for the pane sections in the form.
    module_load_include('inc', 'paddle_panes',
      'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);

    form_load_include($form_state, 'inc', 'paddle_cultuurnet',
      'plugins/content_types/uitdatabank_pane');

    return $form;
  }
  else {
    form_set_error('', t('Fill the application key and shared secret ') . l(t('here'), '/admin/paddlet_store/app/paddle_cultuurnet/configure'));
  }
}

/**
 * Callback to render the search field form.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_search($form, &$form_state) {
  return $form;
}

/**
 * Callback to render the spotlight form.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_spotlight($form, &$form_state) {
  $conf = $form_state['conf'];

  // Add an autocomplete field for selecting an event.
  $form['section_body']['event'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => isset($conf['event']) ? $conf['event'] : '',
    '#size' => 60,
    '#maxlength' => 300,
    '#autocomplete_path' => 'admin/paddle_cultuurnet/event_autocomplete_callback/',
    '#attributes' => array('placeholder' => t('Search for an event by name. (Insert at least three characters)')),
  );

  return $form;
}

/**
 * Callback to render the list form.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_list($form, &$form_state) {
  $conf = $form_state['conf'];

  // Add an autocomplete field for selecting a tag.
  $form['section_body']['tag'] = array(
    '#type' => 'textfield',
    '#title' => t('Tag:'),
    '#required' => TRUE,
    '#default_value' => isset($conf['tag']) ? $conf['tag'] : '',
    '#size' => 60,
    '#maxlength' => 300,
    '#description' => t('Search for events by a tag from within the UiTdatabank.'),
  );

  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('Show'),
    '#options' => array(
      'titles' => t('Titles'),
      'summaries' => t('Summaries'),
      'spotlight' => t('In the spotlight'),
    ),
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'titles',
  );

  $form['section_body']['view_mode']['summaries']["#description"] = t('Featured image, name and description');

  $form['section_body']['amount'] = array(
    '#type' => 'textfield',
    '#title' => t('Amount:'),
    '#required' => TRUE,
    '#default_value' => isset($conf['amount']) ? $conf['amount'] : '5',
    '#size' => 4,
    '#maxlength' => 4,
  );

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_validate($form, &$form_state) {
  $values = $form_state['values'];

  if ($values['selection_type'] == 'spotlight') {
    if (empty($values['event'])) {
      form_set_error('', t('You need to select an event.'));
    }
  }

  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_submit($form, &$form_state) {
  $values = $form_state['values'];

  // Save single values into configuration.
  $keys = array('selection_type', 'event', 'tag', 'view_mode', 'amount');
  foreach ($keys as $index) {
    if (isset($values[$index])) {
      $form_state['conf'][$index] = $values[$index];
    }
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Ajax callback to render the form after changing the selection type.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_edit_form_selection_type_ajax_callback($form, &$form_state) {
  return $form['pane_settings']['section_body'];
}

/**
 * Provides information about the current page configuration.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return array
 *   An array representing the actual configuration.
 */
function paddle_cultuurnet_uitdatabank_pane_content_type_configuration_info($conf) {
  $info = array();

  switch ($conf['selection_type']) {
    case 'spotlight':
      $info[] = t('In the Spotlight');
      break;
    case 'search':
      $info[] = t('Search field');
      break;
    case 'list':
      $info[] = t('List');
      break;
  }

  return $info;
}
