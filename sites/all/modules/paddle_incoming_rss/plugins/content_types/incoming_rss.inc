<?php

/**
 * @file
 * Plugin to show an incoming RSS lists.
 */

$plugin = array(
  'title' => t('Incoming RSS'),
  'no title override' => TRUE,
  'description' => t('Add an incoming RSS list.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Incoming RSS'), -20),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_incoming_rss_incoming_rss_content_type_render($subtype, $conf, $args, $context) {
  // Load our feed and our view.
  $entity = entity_load_single('paddle_incoming_rss_feed', $conf['rss_list']);
  $view = views_get_view('paddle_incoming_rss_feed_items_list');

  // If no feed or view found, do not render anything.
  if (!$entity || !$view) {
    return array();
  }

  // Set the view configuration.
  $view->set_display('default');
  $view->display_handler->options['style_plugin'] = ($conf['view_mode'] == 'title') ? 'list' : 'default';
  $view->display_handler->options['row_options']['view_mode'] = $conf['view_mode'];
  $view->display_handler->options['pager']['options']['items_per_page'] = $conf['number_items'];

  // Set the feed it as argument.
  $view->pre_execute(array($entity->identifier()));
  $output = $view->render();

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block = new stdClass();
  $block->title = '';
  $block->content = $output;

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_incoming_rss_incoming_rss_content_type_edit_form($form, &$form_state) {
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

  $rss_lists = entity_load('paddle_incoming_rss_feed');
  if (!count($rss_lists)) {
    $form['section_body']['rss_list'] = array(
      '#markup' => t('No Incoming RSS lists added yet.'),
    );
  }
  else {
    $options = array();
    foreach ($rss_lists as $entity) {
      $options[$entity->identifier()] = $entity->label();
    }
    $form['section_body']['rss_list'] = array(
      '#type' => 'select',
      '#default_value' => isset($conf['rss_list']) ? $conf['rss_list'] : NULL,
      '#required' => TRUE,
      '#title' => t('Select an Incoming RSS list'),
      '#options' => $options,
    );

    $form['section_body']['view_mode'] = array(
      '#type' => 'radios',
      '#title' => t('View mode'),
      '#options' => array('title' => t('Titles only'), 'magazine' => t('Magazine mode')),
      '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'title',
    );

    $form['section_body']['number_items'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('Number of items to display') . ':',
      '#default_value' => isset($conf['number_items']) ? $conf['number_items'] : variable_get('pane_listing_number_items', 5),
    );
  }

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_incoming_rss_incoming_rss_content_type_edit_form_validate($form, &$form_state) {
  $values = $form_state['values'];
  // Check that the "Number of items" field is a number bigger than zero.
  if (!filter_var($values['number_items'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))) {
    form_set_error('number_items', t('The number of items must be a valid number bigger than zero.'));
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
function paddle_incoming_rss_incoming_rss_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['rss_list'] = $form_state['values']['rss_list'];
  $form_state['conf']['view_mode'] = $form_state['values']['view_mode'];
  $form_state['conf']['number_items'] = $form_state['values']['number_items'];

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
