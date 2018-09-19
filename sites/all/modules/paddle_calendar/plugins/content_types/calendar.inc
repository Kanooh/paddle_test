<?php

/**
 * @file
 * Plugin to show a calendar pane.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Calendar'),
  'no title override' => TRUE,
  'description' => t('Add a calendar.'),
  'category' => t('Paddle Calendar'),
  'defaults' => array(),
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback.
 */
function paddle_calendar_calendar_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  // Fetch the configured tags. If there are any tags configured, implode them
  // with the plus sign so they get handled as OR.
  $tags = !empty($conf['calendar_tags']) ? implode('+', $conf['calendar_tags']) : 'all';

  // Preview the view with the correct parameters: NULL for the date, so
  // the current month will be picked up, and with the tags selected.
  $output = views_embed_view('calendar_pane', $conf['view_mode'], NULL, $tags);

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_calendar_calendar_content_type_edit_form($form, &$form_state) {
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

  $options = array();
  $calendar_terms = paddle_calendar_get_calendar_tags($form_state['contexts']['panelizer']->data->language);
  foreach (taxonomy_term_load_multiple($calendar_terms) as $term) {
    $options[$term->tid] = $term->name;
  }

  if (!empty($options)) {
    $tags = isset($conf['calendar_tags']) ? $conf['calendar_tags'] : array();
    $form['section_body']['calendar_tags'] = array(
      '#type' => 'checkboxes',
      '#options' => $options,
      '#title' => t('Tags'),
      '#default_value' => $tags,
    );
  }
  else {
    $form['section_body']['empty_text'] = array(
      '#markup' => '<label>' .t('Tags') . '</label><div>' . t('You can use tags to categorize calendars. Currently you have no tagged calendar items.') . '</div>',
    );
  }

  $options = array(
    'month_calendar_view' => t('Month calendar view'),
    'month_list_view' => t('Month list view'),
    'month_list_view_image' => t('Month list view with featured image'),
    'week_list_view' => t('Week list view'),
  );
  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('View mode'),
    '#options' => $options,
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'month_calendar_view',
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_calendar', 'plugins/content_types/calendar');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_calendar_calendar_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_calendar_calendar_content_type_edit_form_submit($form, &$form_state) {
  // Save the configuration.
  $form_state['conf']['view_mode'] = $form_state['values']['view_mode'];

  if (!empty($form_state['values']['calendar_tags'])) {
    $form_state['conf']['calendar_tags'] = array_filter(array_values($form_state['values']['calendar_tags']));
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
