<?php

/**
 * @file
 * CTools content type plugin to embed the document links of EBL publications.
 */

$plugin = array(
  'title' => t('EBL publication links'),
  'description' => t('Show the document links of the EBL publication.'),
  'category' => t('Paddle EBL'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_ebl_ebl_links_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  if (!empty($wrapper->field_paddle_ebl_document_links) && !empty($wrapper->field_paddle_ebl_document_links->value())) {
    $document_links = $wrapper->field_paddle_ebl_document_links->value();
    $list_items = array();

    foreach ($document_links as $document_link) {
      $path_info = pathinfo($document_link['title']);

      $file_extension = "";
      if (!empty($path_info['extension'])) {
        $file_extension = $path_info['extension'];
      }

      // Render the icon based on the file extension.
      if (!empty($file_extension)) {
        $icon = theme('paddle_core_icon', array(
          'type' => 'file',
          'name' => $file_extension,
          'attributes' => array('class' => 'file-' . drupal_html_class($file_extension)),
        ));
      }
      else {
        $icon = theme('paddle_core_icon', array(
          'name' => 'file',
          'type' => 'file',
        ));
      }

      // Get the HTML for the list item.
      $item = theme('paddle_core_listing_table_item', array(
        'content' => $document_link['title'],
        'icon' => $icon,
        'extension' => $file_extension,
      ));

      $link_options['attributes']['target'] = '_blank';
      $link_options['html'] = TRUE;

      $list_item = l($item, $document_link['value'], $link_options);
      $list_items[] = $list_item;
    }
    $variables['documents_list'] = theme('paddle_core_listing_table',
      array('items' => $list_items));
  }

  if (!empty($variables)) {
    $output = theme('paddle_ebl_links', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Download'),
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
      paddle_panes_pane_sections_render($output, $conf, $context);
    }

    $block->content = $output;
  }

  return $block;
}
