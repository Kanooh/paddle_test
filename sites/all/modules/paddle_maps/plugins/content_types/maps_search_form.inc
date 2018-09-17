<?php

/**
 * @file
 * Content type plugin to render the search form of an maps search page.
 */

$plugin = array(
  'title' => t('Search form'),
  'description' => t('Renders the search form for an maps search page.'),
  'category' => t('Paddle maps Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'use_default_btn_text' => 1,
    'custom_btn_text' => t('Search'),
  ),
);

/**
 * Render callback.
 */
function paddle_maps_maps_search_form_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $node = $context->data;

  module_load_include('inc', 'paddle_maps', 'paddle_maps.forms');

  $form = drupal_get_form('paddle_maps_form', $node, $conf);

  // Wrap the content like a "classic" pane content.
  // @see paddle_panes_pane_sections_render()
  $block->content['body'] = array(
    '#type' => 'container',
    '#weight' => 0,
    'content' => $form,
  );

  return $block;
}
