<?php
/**
 * @file
 * Ctools Content Type Plugin for the assignee uid field in the node edit form.
 */

$plugin = array(
  'single' => TRUE,
  'title' => 'Node form assignee uid',
  'description' => '',
  'required context' => new ctools_context_required(t('Form'), 'node_form'),
  'category' => t('Form'),
);

/**
 * Render callback for the assignee uid plugin.
 */
function paddle_content_manager_node_form_assignee_uid_field_content_type_render($subtype, $conf, $panel_args, &$context) {
  if (isset($context->form)) {
    $block = new stdClass();
    $block->module = t('node_form');
    $block->title = '';
    $block->delta = 'assignee_uid_node_form';

    if (isset($context->form['assignee_uid'])) {
      $block->content['assignee_uid'] = $context->form['assignee_uid'];
    }
    return $block;
  }
}

/**
 * Settings form builder callback for the assignee uid field plugin.
 */
function paddle_content_manager_node_form_assignee_uid_field_content_type_edit_form($form, &$form_state) {
  // Provide a blank form so we have a place to have context setting.
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_node_form_assignee_uid_field_content_type_admin_title($subtype, $conf, $context) {
  return t('Node form assignee uid');
}
