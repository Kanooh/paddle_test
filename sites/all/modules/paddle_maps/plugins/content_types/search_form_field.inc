<?php

/**
 * @file
 * Content type plugin to render the search form field.
 */

$plugin = array(
  'title' => t('Search form field'),
  'description' => t('Renders the search form field for an maps page edit page.'),
  'category' => t('Paddle Maps Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_maps_search_form_field_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $block->content = array();
  $block->content['search_form'] = $context->form['search_form'];

  return $block;
}

/**
 * Settings form callback.
 */
function paddle_maps_search_form_field_content_type_edit_form($form, &$form_state) {
  return $form;
}
