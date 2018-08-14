<?php

/**
 * @file
 * Plugin to show an embed widget.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Embed widget'),
  'no title override' => TRUE,
  'description' => t('Add an embed widget.'),
  'category' => t('Paddle Content Manager'),
  'editor render callback' => 'paddle_embed_widget_content_type_editor_render',
  'defaults' => array(
    'wid' => 0,
  ),
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback.
 */
function paddle_embed_embed_widget_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  if (empty($conf['wid']) || !($widget = paddle_embed_widget_load($conf['wid']))) {
    return $block;
  }

  // Do NOT filter this code. That would defeat the whole point of the paddlet.
  $output = $widget->embed_code;

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Render callback for the pane to display in the panels editor.
 *
 * Note that this is supposed to return a string, as it overrides the content
 * property of the content of the block rendered by the default callback.
 */
function paddle_embed_widget_content_type_editor_render($subtype, $conf, $args, $context) {
  // If the pane is configured correctly and the chosen widget exists, simply
  // provide a placeholder message to indicate what widget will be rendered.
  if (!empty($conf['wid']) && $widget = paddle_embed_widget_load($conf['wid'])) {
    return t('This will render "%title" widget on the front-end.', array('%title' => $widget->title));
  }

  // Otherwise provide a message telling the user to select a different widget
  // or remove the pane.
  return t('The widget this pane was using has been removed. Please remove or reconfigure this pane.');
}

/**
 * Settings form builder callback.
 */
function paddle_embed_embed_widget_content_type_edit_form($form, &$form_state) {
  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $widget_options = array();
  $widgets = entity_load('paddle_embed_widget');
  foreach ($widgets as $widget) {
    $widget_options[$widget->wid] = $widget->title;
  }

  if (!empty($widget_options)) {
    $form['section_body']['wid'] = array(
      '#title' => t('Select a widget'),
      '#type' => 'radios',
      '#options' => $widget_options,
      '#default_value' => $form_state['conf']['wid'],
      '#empty_value' => 0,
      '#empty_option' => '',
      '#required' => TRUE,
    );
  }
  else {
    $form['section_body']['wid'] = array(
      '#type' => 'item',
      '#markup' => t('No widgets available.'),
      '#prefix' => '<strong>',
      '#suffix' => '</strong>',
    );
  }

  $form['section_body']['info'] = array(
    '#type' => 'item',
    '#markup' => t('To add new widgets, please contact your site manager.'),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_embed', 'plugins/content_types/embed_widget');

  return $form;
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function paddle_embed_form_paddle_embed_embed_widget_content_type_edit_form_alter(&$form, &$form_state) {
  // Remove the "Finish" and "Cancel" buttons if no widgets are available. Also
  // remove the top & bottom section settings. We can't just skip them in the
  // config form function because we need the function that adds the top &
  // bottom sections to also add some containers for styling.
  if (!isset($form['pane_settings']['section_body']['wid']['#options'])) {
    unset($form['buttons']);
    unset($form['pane_settings']['top']);
    unset($form['pane_settings']['bottom']);
  }
}

/**
 * Validate handler for the edit form.
 */
function paddle_embed_embed_widget_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_embed_embed_widget_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['wid'] = $form_state['values']['wid'];

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
