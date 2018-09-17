<?php

/**
 * @file
 * Content type plugin to render the publication year filter of an advanced search page.
 */

$plugin = array(
  'title' => t('Publication year filter'),
  'description' => t('Renders the publication year filter for an advanced search page.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_publication_publication_year_filter_content_type_render($subtype, $conf, $args, $context) {
  // Verify if the authors filter is enabled.
  if (empty($conf['publication_year_filter'])) {
    return array();
  }

  $terms = paddle_taxonomy_manager_get_terms_from_vocabulary('paddle_publication_year');

  // If the term is not found, skip the rendering.
  if (empty($terms)) {
    return array();
  }

  // Retrieve the hashed delta used to declare the related block. Abort if
  // none is found.
  $hashed_delta = paddle_search_get_taxonomy_vocabulary_facet_hashed_delta('publication_year_filter', 'paddle_publication');
  if (empty($hashed_delta)) {
    return array();
  }

  // Render the facet block.
  $block = paddle_search_render_facet_block($hashed_delta);

  return $block;
}
