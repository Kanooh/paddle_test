<?php

/**
 * @file
 * Content type plugin to render the search results of an advanced search page.
 */

$plugin = array(
  'title' => t('Search results'),
  'description' => t('Renders the search results field for an advanced search page.'),
  'category' => t('Paddle Advanced Search'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'tids' => array(),
  ),
  'render first' => TRUE,
);

/**
 * Render callback.
 */
function paddle_advanced_search_advanced_search_results_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $node = $context->data;

  // Extract the content type filter settings from the node.
  $raw_content_types = field_get_items('node', $node, 'field_adv_search_content_types');
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
  $content = views_embed_view('paddle_advanced_search', 'default', $content_types, $tids, $keywords);

  // Render the related sorts block.
  $block_content = module_invoke('search_api_sorts', 'block_view', 'search-sorts');
  $block->content = render($block_content['content']);
  $block->content .= $content;

  return $block;
}
