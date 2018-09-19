<?php

/**
 * @file
 * Content type plugin to render the authors filter of an advanced search page.
 */

$plugin = array(
  'title' => t('Authors filter'),
  'description' => t('Renders the authors filter for an advanced search page.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_publication_authors_filter_content_type_render($subtype, $conf, $args, $context) {
  // Verify if the authors filter is enabled.
  if (empty($conf['authors_filter'])) {
    return array();
  }

  $terms = paddle_taxonomy_manager_get_terms_from_vocabulary('paddle_authors');

  // If the term is not found, skip the rendering.
  if (empty($terms)) {
    return array();
  }

  // Retrieve the hashed delta used to declare the related block. Abort if
  // none is found.
  $hashed_delta = paddle_search_get_taxonomy_vocabulary_facet_hashed_delta('authors_filter', 'paddle_publication');
  if (empty($hashed_delta)) {
    return array();
  }

  // Render the facet block.
  $block = paddle_search_render_facet_block($hashed_delta);

  return $block;
}
