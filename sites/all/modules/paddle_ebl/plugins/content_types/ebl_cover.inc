<?php

/**
 * @file
 * CTools content type plugin to embed the cover image of EBL publications.
 */

$plugin = array(
  'title' => t('EBL publication cover image'),
  'description' => t('Show the cover image of the EBL publication.'),
  'category' => t('Paddle EBL'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_ebl_ebl_cover_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  if (!empty($wrapper->field_paddle_ebl_main_image) && !empty($wrapper->field_paddle_ebl_main_image->value())) {
    $main_image_field = $wrapper->field_paddle_ebl_main_image->value();
    $main_image = scald_atom_load($main_image_field['sid']);
    $image_wrapper = entity_metadata_wrapper('scald_atom', $main_image);

    if (!empty($image_wrapper->field_scald_alt_tag) && !empty($image_wrapper->field_scald_alt_tag->value())) {
      $variables['alt_tag'] = $image_wrapper->field_scald_alt_tag->value();
    }

    if (!empty($main_image->file_source)) {
      $variables['image_url'] = file_create_url(image_style_path('large', $main_image->file_source));
    }

    if (!empty($wrapper->field_paddle_ebl_issuu_link) && !empty($wrapper->field_paddle_ebl_issuu_link->value())) {
      $link = $wrapper->field_paddle_ebl_issuu_viewer->value();
      $variables['issuu_link'] = $link['value'];
    }
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_ebl_cover', $variables);
  }

  return $block;
}
