<?php

/**
 * @file
 * CTools content type plugin to embed a news info banner.
 */

$plugin = array(
  'title' => t('News info banner'),
  'no title override' => TRUE,
  'description' => t('News info banner.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'news_info_banner_content_type_edit_form',
  'render callback' => 'news_info_banner_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function news_info_banner_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';

  $node = $context->data;
  $block->content = theme('paddle_news_item_info', array('date' => $node->created));

  return $block;
}

/**
 * Edit form.
 */
function news_info_banner_content_type_edit_form($form, &$form_state) {
  return $form;
}
