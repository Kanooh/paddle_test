<?php

/**
 * @file
 * CTools content type plugin which represents the image pane
 * of an offer content type.
 */

$plugin = array(
  'title' => t('Offer image pane'),
  'no title override' => TRUE,
  'description' => t('The images of the offer content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Offers'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function paddle_holiday_participation_offer_image_pane_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $template = 'paddle_holiday_participation_offer_image_pane';
  $wrapper = entity_metadata_wrapper('node', $node);

  $featured_image = $wrapper->field_paddle_featured_image->value();

  if (!empty($featured_image)) {
    $display = array('label' => 'hidden');
    $main_image = field_view_field('node', $node, 'field_paddle_featured_image', $display);
    $variables['main_image'] = drupal_render($main_image);
  }

  $image_atoms = $wrapper->field_hp_images->value();

  if (!empty($image_atoms)) {
    $images = '';
    libraries_load('colorbox');

    if (!path_is_admin(current_path())) {
      // Add the colorbox JS file.
      drupal_add_js(drupal_get_path('module', 'paddle_scald_image') . '/js/paddle_scald_image_colorbox.js', 'file');
    }

    $attributes = array('class' => array('colorbox-link-group'));

    foreach ($image_atoms as $image_atom) {
      $atom = scald_fetch($image_atom['sid']);

      $render = array(
        '#prefix' => '<div class="photo-album-image">',
        '#markup' => l(
          theme('paddle_scald_render_atom', array('atom' => $atom, 'image_style' => 'square')),
          file_create_url($atom->file_source),
          array('attributes' => $attributes, 'html' => TRUE)
        ),
        '#suffix' => '</div>',
      );
      $images .= drupal_render($render);
    }

    $variables['images'] = $images;
  }

  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}

/**
 * Edit form.
 */
function paddle_holiday_participation_offer_image_pane_content_type_edit_form($form, &$form_state) {
  return $form;
}
