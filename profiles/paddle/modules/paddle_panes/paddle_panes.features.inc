<?php
/**
 * @file
 * paddle_panes.features.inc
 */

/**
 * Implements hook_views_api().
 */
function paddle_panes_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_image_default_styles().
 */
function paddle_panes_image_default_styles() {
  $styles = array();

  // Exported image style: top_section_icon.
  $styles['top_section_icon'] = array(
    'label' => 'Top section icon',
    'effects' => array(
      1 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 45,
          'height' => 45,
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: paddle_listing_thumbnail.
  $styles['paddle_listing_thumbnail'] = array(
      'name' => 'paddle_listing_thumbnail',
      'label' => 'paddle_listing_thumbnail',
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
                  'width' => 55,
                  'height' => 55,
              ),
              'weight' => 1,
          ),
      ),
  );

  return $styles;
}
