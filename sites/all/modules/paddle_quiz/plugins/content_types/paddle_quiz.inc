<?php

/**
 * @file
 * Plugin to show a quiz attached to a quiz page.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Rendered quiz.'),
  'no title override' => TRUE,
  'description' => t('Rendered quiz attached to a quiz page.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => t('Paddle Quiz'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'editor render callback' => 'paddle_quiz_paddle_quiz_content_type_editor_render',
);

/**
 * Render callback.
 */
function paddle_quiz_paddle_quiz_content_type_render($subtype, $conf, $args, $context, $preview = FALSE) {
  $node = $context->data;
  return paddle_quiz_pane_render($node);
}

/**
 * Render callback for the admin node view.
 */
function paddle_quiz_paddle_quiz_content_type_editor_render($subtype, $conf, $args, $context) {
  $node = $context[$conf['context']]->data;
  $block = paddle_quiz_pane_render($node, TRUE);
  return $block->content;
}

/**
 * Does the actual rendering of the quiz pane.
 *
 * @param object $node
 *   Node object of the page.
 * @param bool $preview
 *   When previewing the quiz results are not saved.
 *
 * @return object
 *   Object with the necessary properties for ctools to render the pane.
 */
function paddle_quiz_pane_render($node, $preview = FALSE) {
  // Initialize the pane with a placeholder message.
  $placeholder = theme('paddle_quiz_placeholder', array(
    'message' => t('This quiz is no longer available. We apologize for any inconvenience that this may cause.'),
  ));
  $block = new stdClass();
  $block->title = '';
  $block->content = $placeholder;

  // Return the block with the placeholder if there's no referenced quiz.
  if (empty($node->field_paddle_quiz_reference[LANGUAGE_NONE][0]['target_id'])) {
    return $block;
  }

  // Load the referenced quiz.
  $qid = $node->field_paddle_quiz_reference[LANGUAGE_NONE][0]['target_id'];
  $quiz = paddle_quiz_load($qid);

  // Return the block with the placeholder message if the quiz doesn't exist or
  // it's not published.
  if (empty($quiz) || empty($quiz->status)) {
    drupal_add_css(drupal_get_path('module', 'paddle_quiz') . '/css/paddle_quiz.quiz.css');
    return $block;
  }

  // Load all the field collections for the questions in the quiz entity.
  $quiz->loadQuestions();

  // Build the multi-step form.
  $form_state = array('paddle_quiz' => $quiz, 'preview' => $preview);
  module_load_include('inc', 'paddle_quiz', 'paddle_quiz.participation');
  $form = drupal_build_form('paddle_quiz_participation_form', $form_state);
  $block->content = render($form);

  // Make sure that the comments are rendered directly below the quiz itself.
  if (module_exists('paddle_comment') && variable_get('comment_' . $node->type, COMMENT_NODE_HIDDEN) != COMMENT_NODE_HIDDEN) {
    comment_node_view($node, 'full');
    $block->content .= render($node->content['comments']);
  }

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_quiz_paddle_quiz_content_type_edit_form($form, &$form_state) {
  return $form;
}
