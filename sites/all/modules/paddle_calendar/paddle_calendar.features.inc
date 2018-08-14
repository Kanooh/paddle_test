<?php
/**
 * @file
 * paddle_calendar.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_calendar_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "panelizer" && $api == "panelizer") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function paddle_calendar_views_api($module = NULL, $api = NULL) {
  return array(
    "api" => "3.0",
    'template path' => drupal_get_path('module', 'paddle_calendar') . '/theme',
  );
}

/**
 * Implements hook_node_info().
 */
function paddle_calendar_node_info() {
  $items = array(
    'calendar_item' => array(
      'name' => t('Calendar item'),
      'base' => 'node_content',
      'description' => t('Add a new <em>calendar item</em> to be shown in calendar views.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}

/**
 * Implements hook_image_default_styles().
 */
function paddle_calendar_image_default_styles() {
  $styles = array();

  // Exported image style: paddle_news_thumbnail.
  $styles['paddle_calendar_thumbnail'] = array(
    'name' => 'paddle_calendar_thumbnail',
    'label' => 'paddle_calendar_thumbnail',
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
          'width' => 90,
          'height' => 90,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}
