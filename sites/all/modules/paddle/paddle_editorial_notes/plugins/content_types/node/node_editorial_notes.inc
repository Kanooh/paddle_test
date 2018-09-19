<?php
/**
 * @file
 * Ctools Content Type Plugin for editorial notes on the node view page.
 */

$plugin = array(
  'single' => TRUE,
  'title' => t('Node editorial notes'),
  'description' => t('Shows editorial notes on the node view page.'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'category' => t('Node'),
);

/**
 * Render callback for the editorial notes plugin.
 */
function paddle_editorial_notes_node_editorial_notes_content_type_render($subtype, $conf, $args, &$context) {
  if (isset($context->data->nid)) {
    $block = new stdClass();
    $block->module = t('node');
    $block->title = t('Editorial notes');
    $block->delta = 'editorial_notes_node';

    $block->content = drupal_get_form('paddle_editorial_notes_note_form', $context->data);

    unset($block->content['editorial_notes']['#pre_render']);
    unset($block->content['editorial_notes']['#theme_wrappers']);
    $block->content['editorial_notes']['#type'] = '';

    // Set access to false on the original rather than removing so that
    // vertical tabs doesn't clone it. I think this is due to references.
    $context->form['editorial_notes']['#access'] = FALSE;

    return $block;
  }
}

/**
 * Settings form builder callback.
 */
function paddle_editorial_notes_node_editorial_notes_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_editorial_notes_node_editorial_notes_content_type_admin_title($subtype, $conf, $context) {
  return t('Node editorial notes');
}
