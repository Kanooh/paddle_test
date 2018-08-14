<?php

/**
 * @file
 * CTools content type plugin to embed the Related documents.
 */

$plugin = array(
  'title' => t('Related documents'),
  'description' => t('Related documents of the publication.'),
  'category' => t('Paddle Publication'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_publication_related_documents_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $wrapper = entity_metadata_wrapper('node', $context->data);
  $related_documents = $wrapper->field_paddle_kce_related_docs->value();

  if (!empty($related_documents)) {
    $items = array();
    foreach ($related_documents as $document) {
      // Load the atom.
      $atom = scald_atom_load($document['sid']);

      // Get the url for the atom.
      $url = file_create_url($atom->file_source);
      $link_options['attributes']['target'] = '_blank';
      $link_options['html'] = TRUE;

      // Only certain atom types should have the file type icon.
      // Always use lowercase extension for classes.
      $extension = strtolower(pathinfo($atom->file_source, PATHINFO_EXTENSION));

      // Get the mime type from the file.
      $type = FALSE;
      if (!empty($atom->paddle_scald_file[LANGUAGE_NONE][0]['filemime'])) {
        $type = $atom->paddle_scald_file[LANGUAGE_NONE][0]['filemime'];
      }
      elseif (!empty($atom->base_entity->filemime)) {
        $type = $atom->base_entity->filemime;
      }
      $file_size = $atom->paddle_scald_file[LANGUAGE_NONE][0]['filesize'];

      $attributes = array();
      // If we have found a mime type, add it as class.
      if ($type) {
        $attributes['class'] = 'file-' . drupal_html_class($type);
      }

      $icon = theme('paddle_core_icon', array(
        'type' => 'file',
        'name' => $extension,
        'attributes' => $attributes,
      ));

      // Get the HTML for the list item.
      $item = theme('paddle_core_listing_table_item', array(
        'content' => $atom->title,
        'icon' => $icon,
        'extension' => $extension,
        'size' => paddle_scald_file_format_size($file_size),
      ));

      // If an url exists, wrap the item inside a link. If no url was found, we're
      // out of luck.
      if (!empty($url)) {
        $item = l($item, $url, $link_options);
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
        'section_text' => t('Related documents'),
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
