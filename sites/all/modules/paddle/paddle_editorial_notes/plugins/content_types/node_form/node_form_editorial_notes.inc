<?php
/**
 * @file
 * Ctools Content Type Plugin for editorial notes in the node edit form.
 */

$plugin = array(
  'single' => TRUE,
  'title' => t('Node form editorial notes'),
  'description' => t('Editorial notes on the Node add/edit form.'),
  'required context' => new ctools_context_required(t('Form'), 'node_form'),
  'category' => t('Form'),
);

/**
 * Render callback for the editorial notes plugin.
 */
function paddle_editorial_notes_node_form_editorial_notes_content_type_render($subtype, $conf, $panel_args, &$context) {
  if (isset($context->form)) {
    $block = new stdClass();
    $block->module = t('node_form');
    $block->title = t('Editorial notes');
    $block->delta = 'editorial_notes_node_form';

    if (isset($context->form['editorial_notes'])) {
      $block->content['editorial_notes'] = $context->form['editorial_notes'];

      unset($block->content['editorial_notes']['#pre_render']);
      unset($block->content['editorial_notes']['#theme_wrappers']);
      $block->content['editorial_notes']['#type'] = '';

      // Set access to false on the original rather than removing so that
      // vertical tabs doesn't clone it. I think this is due to references.
      $context->form['editorial_notes']['#access'] = FALSE;
    }
    return $block;
  }
}

/**
 * Settings form builder callback for the editorial notes plugin.
 */
function paddle_editorial_notes_node_form_editorial_notes_content_type_edit_form($form, &$form_state) {
  // Provide a blank form so we have a place to have context setting.
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_editorial_notes_node_form_editorial_notes_content_type_admin_title($subtype, $conf, $context) {
  return t('Node form editorial notes');
}
