<?php

/**
 * @file
 * Content type plugin to render the search form of an advanced search page.
 */

$plugin = array(
  'title' => t('Search form'),
  'description' => t('Renders the search form for an advanced search page.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'use_default_btn_text' => 1,
    'custom_btn_text' => t('Search'),
  ),
);

/**
 * Render callback.
 */
function paddle_advanced_search_advanced_search_form_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $node = $context->data;

  module_load_include('inc', 'paddle_advanced_search', 'paddle_advanced_search.forms');
  $form = drupal_get_form('paddle_advanced_search_form', $node, $conf);

  // Wrap the content like a "classic" pane content.
  // @see paddle_panes_pane_sections_render()
  $block->content['body'] = array(
    '#type' => 'container',
    '#attributes' => array(
      'class' => array('pane-section-body'),
    ),
    '#weight' => 0,
    'content' => $form,
  );

  return $block;
}
