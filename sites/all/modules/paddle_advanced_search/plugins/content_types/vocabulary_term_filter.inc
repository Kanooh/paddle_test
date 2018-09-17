<?php

/**
 * @file
 * Content type plugin to render a filter on a vocabulary term.
 */

$plugin = array(
  'title' => t('Vocabulary term filter'),
  'description' => t('Renders a filter on a vocabulary term.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_advanced_search_vocabulary_term_filter_content_type_render($subtype, $conf, $args, $context) {
  // Verify if the term is still a root term. If not, do not render the pane.
  $tids = _paddle_search_filter_root_tids(array($conf['tid']));
  if (empty($tids)) {
    return array();
  }

  $term = taxonomy_term_load($conf['tid']);

  // If the term is not found, skip the rendering.
  if (empty($term)) {
    return array();
  }

  // Retrieve the hashed delta used to declare the related block. Abort if
  // none is found.
  $hashed_delta = _paddle_advanced_search_get_taxonomy_term_facet_hashed_delta($conf['tid'], $conf['mode']);
  if (empty($hashed_delta)) {
    return array();
  }

  // Render the related block as the CTools block content type does.
  $block_view = module_invoke('facetapi', 'block_view', $hashed_delta);

  // If the block view is empty, the facet is not being rendered.
  if (empty($block_view)) {
    return array();
  }

  // Render the facet block.
  $block = paddle_search_render_facet_block($hashed_delta);
  // Add a class to the pane to expose the display mode this pane is rendering.
  $block->css_class = 'pane-vocabulary-term-filter--mode-' . drupal_clean_css_identifier($conf['mode']);
  $block->css_class .= ' pane-vocabulary-term-filter--tid-' . drupal_clean_css_identifier($conf['tid']);

  return $block;
}
