<?php
/**
 * @file
 * paddle_carousel.features.inc
 */

/**
 * Implements hook_image_default_styles().
 */
function paddle_carousel_image_default_styles() {
  $styles = array();

  // Exported image style: paddle_carousel_slide.
  $styles['paddle_carousel_slide'] = array(
    'name' => 'paddle_carousel_slide',
    'label' => 'paddle_carousel_slide',
    'effects' => array(
      1 => array(
        'label' => 'Scale and crop',
        'help' => 'Scale and crop will maintain the aspect-ratio of the original image, then crop the larger dimension. This is most useful for creating perfectly square thumbnails without stretching the image.',
        'effect callback' => 'image_scale_and_crop_effect',
        'dimensions callback' => 'image_resize_dimensions',
        'form callback' => 'image_resize_form',
        'summary theme' => 'image_resize_summary',
        'module' => 'image',
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 1175,
          'height' => 881,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}
