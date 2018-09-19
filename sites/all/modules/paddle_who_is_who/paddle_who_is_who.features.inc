<?php
/**
 * @file
 * paddle_who_is_who.features.inc
 */

/**
 * Implements hook_image_default_styles().
 */
function paddle_who_is_who_image_default_styles() {
  $styles = array();

  // Exported image style: who_is_who_thumbnail.
  $styles['who_is_who_thumbnail'] = array(
    'label' => 'who is who thumbnail',
    'effects' => array(
      1 => array(
        'name' => 'image_scale_and_crop',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'data' => array(
          'width' => 150,
          'height' => 150,
          'upscale' => 1,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}
