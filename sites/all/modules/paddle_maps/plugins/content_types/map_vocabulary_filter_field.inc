<?php

/**
 * @file
 * Content type plugin to render the vocabulary filter field.
 */

$plugin = array(
  'title' => t('Map vocabulary filter field'),
  'description' => t('Renders the vocabulary filters field for an maps page.'),
  'category' => t('Paddle Maps'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_maps_map_vocabulary_filter_field_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $block->content = array();
  $block->content['vocabulary_filter'] = $context->form['vocabulary_filter'];

  return $block;
}

/**
 * Settings form callback.
 */
function paddle_maps_map_vocabulary_filter_field_content_type_edit_form($form, &$form_state) {
  return $form;
}
