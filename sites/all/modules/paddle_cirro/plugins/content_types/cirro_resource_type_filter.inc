<?php

/**
 * @file
 * Content type plugin to render the CIRRO resource type filter of an advanced search page.
 */

$plugin = array(
  'title' => t('CIRRO resource type filter'),
  'description' => t('Renders the CIRRO resource type filter for an advanced search page.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_cirro_cirro_resource_type_filter_content_type_render($subtype, $conf, $args, $context) {
  // Verify if the cirro resource type filter is enabled.
  if (empty($conf['cirro_resource_type_filter'])) {
    return array();
  }

  // Retrieve the hashed delta used to declare the related block. Abort if
  // none is found.
  $hashed_delta = _paddle_cirro_get_cirro_resource_type_facet_hashed_delta();
  if (empty($hashed_delta)) {
    return array();
  }

  // Render the facet block.
  $block = paddle_search_render_facet_block($hashed_delta);

  return $block;
}
