<?php

/**
 * @file
 * Plugin to provide a node creation date field on the node form.
 */

/**
 * Plugin declaration.
 * Copied and adjusted from node_form_author plugin in the ctools module.
 */
$plugin = array(
  'single' => TRUE,
  'icon' => 'icon_node_form.png',
  'title' => t('Node form creation date field'),
  'description' => t('Creation date field on the Node form.'),
  'required context' => new ctools_context_required(t('Form'), 'node_form'),
  'category' => t('Form'),
);

/**
 * Render callback.
 */
function paddle_content_manager_node_form_creation_date_content_type_render($subtype, $conf, $panel_args, &$context) {
  $block = new stdClass();
  $block->module = t('node_form');

  $block->title = '';
  $block->content = array();

  if (isset($context->form)) {
    if (!empty($context->form['creation_date'])) {
      $block->title = t('Creation date');

      $block->content['creation_date'] = $context->form['creation_date'];
      unset($block->content['creation_date']['#title']);
      $context->form['creation_date']['#access'] = FALSE;
    }
  }

  return $block;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_node_form_creation_date_content_type_admin_title($subtype, $conf, $context) {
  return t('"@s" node form creation date', array('@s' => $context->identifier));
}

/**
 * Settings form builder callback.
 */
function paddle_content_manager_node_form_creation_date_content_type_edit_form($form, &$form_state) {
  // Provide a blank form so we have a place to have context setting.
  return $form;
}
