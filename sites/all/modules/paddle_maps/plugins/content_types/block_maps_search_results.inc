<?php

/**
 * @file
 * Content type plugin to render the search results of an maps search page.
 */

$plugin = array(
  'title' => t('Map results'),
  'description' => t('Renders the search results field for an maps search page.'),
  'category' => t('Paddle Maps Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'tids' => array(),
  ),
  'render first' => TRUE,
);

/**
 * Render callback.
 */
function paddle_maps_block_maps_search_results_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $node = $context->data;

  // Extract the content type filter settings from the node.
  $raw_content_types = field_get_items('node', $node, 'field_map_content_types');
  if ($raw_content_types) {
    // The value returned is an array of arrays, so we need to convert it to a
    // concatenation of strings.
    /* @var array $raw_content_types */
    $content_types = implode('+', array_column($raw_content_types, 'value'));
  }
  else {
    // We have to use the 'all' value which skip filtering on this argument.
    // This is needed because otherwise views won't apply the next filters too.
    // @see view::_build_arguments()
    $content_types = 'all';
  }

  // Get all the enabled taxonomy terms. Again use NULL for empty values.
  if (!empty($conf['tids'])) {
    // Only apply filter on root terms. This is needed because taxonomy term
    // might have been changed after the configuration was saved.
    $tids = implode('+', _paddle_search_filter_root_tids($conf['tids']));
  }
  else {
    // Same as above.
    $tids = 'all';
  }

  // Verify if a keyword search was launched.
  if (isset($_GET['search']) && strlen($_GET['search'])) {
    $keywords = $_GET['search'];
  }
  else {
    $keywords = NULL;
  }

  // Run the search.
  $content_maps = views_embed_view('paddle_maps', 'block_maps', $content_types, $tids, $keywords);
  $content_maps_text = views_embed_view('paddle_maps', 'block_maps_text', $content_types, $tids, $keywords);

  $block->content = $content_maps;
  $block->content .= $content_maps_text;

  // Load CSS as if this is a real Search API Page.
  $base_path = drupal_get_path('module', 'search_api_page') . '/';
  drupal_add_css($base_path . 'search_api_page.css');

  return $block;
}
