<?php

/**
 * @file
 * Plugin to provide a content type for the poll additional fields.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Poll Additional Fields'),
  'description' => t('Renders the additional poll fields which are not served trough field API.'),
  'category' => t('Paddle Poll'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'extended' => FALSE,
    'collapsible' => FALSE,
    'horizontal' => FALSE,
  ),
);

/**
 * Render callback.
 */
function paddle_poll_poll_additional_fields_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $form = array();
  $form['choice_wrapper'] = $context->form['choice_wrapper'];
  $form['settings'] = $context->form['settings']['active'];

  foreach (element_children($form['choice_wrapper']['choice']) as $key => $value) {
    $form['choice_wrapper']['choice'][$value]['chvotes']['#access'] = FALSE;
  }

  $block->content = $form;

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_poll_poll_additional_fields_content_type_edit_form($form, &$form_state) {
  return $form;
}
