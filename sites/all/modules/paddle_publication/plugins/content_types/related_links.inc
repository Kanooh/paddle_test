<?php

/**
 * @file
 * CTools content type plugin to embed the Related links.
 */

$plugin = array(
  'title' => t('Related links'),
  'description' => t('Related links of the publication.'),
  'category' => t('Paddle Publication'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_publication_related_links_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $wrapper = entity_metadata_wrapper('node', $context->data);
  $related_links = $wrapper->field_paddle_kce_related_links->value();

  if (!empty($related_links)) {
    $items = array();

    foreach ($related_links as $link) {
      $link_options = array();
      if (!empty($link['attributes']['target'])) {
        $link_options['attributes'] = $link['attributes'];
      }

      $link_options['html'] = TRUE;

      // Get the HTML for the list item.
      $item = theme('paddle_core_listing_table_item', array(
        'content' => $link['title'],
      ));

      // If an url exists, wrap the item inside a link. If no url was found, we're
      // out of luck.
      if (!empty($link['url'])) {
        $item = l($item, $link['url'], $link_options);
      }

      // Add the item to the list of which we will generate the listing.
      $items[] = $item;
    }

    // Put all items in a nice listing "table".
    $output = theme('paddle_core_listing_table', array('items' => $items));

    $block->content = $output;

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Related links'),
        'section_content_type' => 'text',
        'section_image' => array(
          'sid' => array(),
          'style' => '',
        ),
        'icon_image' => array(),
        'section_url_type' => 'no_link',
        'section_internal_url' => '',
        'section_external_url' => 'http://',
      );

      module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
      paddle_panes_pane_sections_render($block->content, $conf, $context);
    }
  }

  return $block;
}
